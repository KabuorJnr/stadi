<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\GateLog;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScannerController extends Controller
{
    /**
     * POST /api/validate-ticket
     *
     * Physical QR scanner hits this endpoint at each of the 4 gates.
     * Uses row-level locking to prevent double-scan race conditions.
     */
    public function validate(Request $request): JsonResponse
    {
        $request->validate([
            'qr_hash'    => 'required|string|size:64',
            'gate_number' => 'required|integer|between:1,' . config('stadium.gate_count', 4),
            'device_id'  => 'nullable|string|max:64',
        ]);

        $qrHash    = $request->input('qr_hash');
        $gate      = (int) $request->input('gate_number');
        $deviceId  = $request->input('device_id');

        try {
            return DB::transaction(function () use ($qrHash, $gate, $deviceId) {
                $ticket = Ticket::where('qr_hash', $qrHash)->lockForUpdate()->first();

                if (!$ticket)                        return $this->red(__('scanner.ticket_not_found'));
                if ($ticket->status === 'scanned')   return $this->red(__('scanner.already_scanned'));
                if ($ticket->status === 'cancelled')  return $this->red(__('scanner.ticket_cancelled'));

                $event = Event::where('id', $ticket->event_id)->lockForUpdate()->first();
                if (!$event || $event->isSoldOut())   return $this->red(__('scanner.stadium_full'));

                $ticket->update(['status' => 'scanned']);
                $event->increment('current_attendance');

                if ($event->fresh()->isSoldOut()) {
                    $event->update(['ticket_sales_open' => false]);
                }

                GateLog::create([
                    'ticket_id'         => $ticket->id,
                    'gate_number'       => $gate,
                    'scanned_at'        => now(),
                    'scanner_device_id' => $deviceId,
                ]);

                return $this->green(__('scanner.success'), [
                    'section' => $ticket->section?->name,
                    'gate'    => $gate,
                ]);
            });
        } catch (\Throwable $e) {
            Log::error('Scanner error', ['hash' => $qrHash, 'error' => $e->getMessage()]);
            return $this->red(__('scanner.system_error'));
        }
    }

    /**
     * GET /api/event/{event}/attendance — live stats for dashboard / Power BI.
     */
    public function attendance(Event $event): JsonResponse
    {
        return response()->json([
            'event_id'           => $event->id,
            'event_name'         => $event->name,
            'current_attendance' => $event->current_attendance,
            'max_capacity'       => $event->max_capacity,
            'remaining'          => $event->remainingCapacity(),
            'gates' => GateLog::whereHas('ticket', fn ($q) => $q->where('event_id', $event->id))
                ->selectRaw('gate_number, COUNT(*) as scans')
                ->groupBy('gate_number')
                ->pluck('scans', 'gate_number'),
        ]);
    }

    private function green(string $msg, array $extra = []): JsonResponse
    {
        return response()->json(array_merge(['result' => 'green', 'message' => $msg], $extra));
    }

    private function red(string $msg): JsonResponse
    {
        return response()->json(['result' => 'red', 'message' => $msg], 200);
    }
}
