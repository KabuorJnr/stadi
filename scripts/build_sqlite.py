"""Build a pre-seeded SQLite database for the Stadi mockup demo."""
import sqlite3, os, hashlib, datetime

DB_PATH = os.path.join(os.path.dirname(__file__), '..', 'database', 'database.sqlite')

# Remove old file if exists
if os.path.exists(DB_PATH):
    os.remove(DB_PATH)

conn = sqlite3.connect(DB_PATH)
c = conn.cursor()

now = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')

# ── Create tables ─────────────────────────────────────────
c.executescript('''
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    phone_number TEXT NOT NULL UNIQUE,
    email TEXT,
    password TEXT,
    role TEXT NOT NULL DEFAULT 'fan',
    preferred_locale TEXT NOT NULL DEFAULT 'en',
    email_verified_at TEXT,
    remember_token TEXT,
    created_at TEXT,
    updated_at TEXT
);

CREATE TABLE events (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    description TEXT,
    event_date TEXT NOT NULL,
    base_ticket_price INTEGER NOT NULL,
    max_capacity INTEGER NOT NULL DEFAULT 20000,
    current_attendance INTEGER NOT NULL DEFAULT 0,
    status TEXT NOT NULL DEFAULT 'upcoming',
    ticket_sales_open INTEGER NOT NULL DEFAULT 1,
    home_team TEXT,
    away_team TEXT,
    competition TEXT,
    poster_url TEXT,
    created_at TEXT,
    updated_at TEXT
);

CREATE TABLE stadium_sections (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    code TEXT NOT NULL UNIQUE,
    capacity INTEGER NOT NULL,
    current_occupancy INTEGER NOT NULL DEFAULT 0,
    price_tier TEXT NOT NULL DEFAULT 'regular',
    color TEXT NOT NULL DEFAULT '#1565c0',
    sort_order INTEGER NOT NULL DEFAULT 0,
    svg_path_id TEXT,
    gate_number INTEGER,
    description TEXT,
    created_at TEXT,
    updated_at TEXT
);

CREATE TABLE tickets (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    event_id INTEGER NOT NULL,
    section_id INTEGER,
    qr_hash TEXT NOT NULL UNIQUE,
    status TEXT NOT NULL DEFAULT 'active',
    created_at TEXT,
    updated_at TEXT
);

CREATE TABLE transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    ticket_id INTEGER,
    user_id INTEGER NOT NULL,
    event_id INTEGER NOT NULL,
    section_id INTEGER,
    mpesa_receipt_number TEXT,
    merchant_request_id TEXT,
    checkout_request_id TEXT,
    amount INTEGER NOT NULL,
    phone_number TEXT NOT NULL,
    status TEXT NOT NULL DEFAULT 'pending',
    channel TEXT NOT NULL DEFAULT 'stk_push',
    raw_callback TEXT,
    created_at TEXT,
    updated_at TEXT
);

CREATE TABLE gate_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    ticket_id INTEGER NOT NULL,
    gate_number INTEGER NOT NULL,
    scanned_at TEXT NOT NULL,
    scanner_device_id TEXT
);

CREATE TABLE password_reset_tokens (
    phone_number TEXT PRIMARY KEY,
    token TEXT NOT NULL,
    created_at TEXT
);

CREATE TABLE sessions (
    id TEXT PRIMARY KEY,
    user_id INTEGER,
    ip_address TEXT,
    user_agent TEXT,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
);

CREATE TABLE migrations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    migration TEXT NOT NULL,
    batch INTEGER NOT NULL
);
''')

# ── Migrations record ────────────────────────────────────
migrations = [
    '0001_01_01_000000_create_users_table',
    '0001_01_01_000001_create_events_table',
    '0001_01_01_000002_create_stadium_sections_table',
    '0001_01_01_000003_create_tickets_table',
    '0001_01_01_000004_create_transactions_table',
    '0001_01_01_000005_create_gate_logs_table',
]
for i, m in enumerate(migrations, 1):
    c.execute('INSERT INTO migrations (migration, batch) VALUES (?, ?)', (m, 1))

# ── Demo user ─────────────────────────────────────────────
c.execute('''INSERT INTO users (name, phone_number, role, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?)''',
          ('Demo Fan', '254712345678', 'fan', now, now))

c.execute('''INSERT INTO users (name, phone_number, role, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?)''',
          ('Admin', '254700000000', 'admin', now, now))

# ── Stadium sections (Raila Odinga Stadium, Kisumu) ──────
sections = [
    ('Main Grandstand VIP',   'VIP', 1500, 'vip',     '#c9a84c', 1, 1, 320),
    ('Main Grandstand',       'MGS', 3500, 'premium', '#2e7d32', 2, 1, 850),
    ('Covered Terrace West',  'CTW', 3000, 'regular', '#1565c0', 3, 2, 600),
    ('Covered Terrace East',  'CTE', 3000, 'regular', '#1565c0', 4, 2, 540),
    ('Open Terrace North',    'OTN', 3000, 'economy', '#7b8794', 5, 3, 1200),
    ('Open Terrace South',    'OTS', 3000, 'economy', '#7b8794', 6, 3, 980),
    ('Terrace Behind Goal A', 'TGA', 1500, 'economy', '#9e9e9e', 7, 4, 400),
    ('Terrace Behind Goal B', 'TGB', 1500, 'economy', '#9e9e9e', 8, 4, 350),
]

for name, code, cap, tier, color, sort, gate, occ in sections:
    c.execute('''INSERT INTO stadium_sections
        (name, code, capacity, current_occupancy, price_tier, color, sort_order, svg_path_id, gate_number, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)''',
        (name, code, cap, occ, tier, color, sort, f'section-{code.lower()}', gate, now, now))

# ── Demo events ───────────────────────────────────────────
events = [
    ('Gor Mahia vs AFC Leopards', 'The Mashemeji Derby — East Africa\'s biggest rivalry! A fierce showdown at Raila Odinga Stadium.',
     '2026-04-12 15:00:00', 200, 20000, 5240, 'upcoming', 1, 'Gor Mahia', 'AFC Leopards', 'FKF Premier League'),
    ('Tusker FC vs KCB', 'Brewers take on the Bankers in a crucial mid-season clash.',
     '2026-04-19 16:00:00', 150, 20000, 2100, 'upcoming', 1, 'Tusker FC', 'KCB', 'FKF Premier League'),
    ('Kenya vs Tanzania', 'AFCON 2027 Qualifier — Harambee Stars host Kilimanjaro Stars in a must-win East African battle.',
     '2026-05-03 17:00:00', 500, 20000, 8500, 'upcoming', 1, 'Kenya', 'Tanzania', 'AFCON 2027 Qualifier'),
    ('Bandari vs Kariobangi Sharks', 'Coastal side visits Kisumu for a mid-table battle.',
     '2026-05-10 15:30:00', 100, 20000, 0, 'upcoming', 1, 'Bandari', 'Kariobangi Sharks', 'FKF Premier League'),
]

for name, desc, date, price, cap, att, status, sales, home, away, comp in events:
    c.execute('''INSERT INTO events
        (name, description, event_date, base_ticket_price, max_capacity, current_attendance, status, ticket_sales_open, home_team, away_team, competition, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)''',
        (name, desc, date, price, cap, att, status, sales, home, away, comp, now, now))

# ── Demo tickets ──────────────────────────────────────────
for i in range(1, 6):
    qr = hashlib.sha256(f'demo-ticket-{i}'.encode()).hexdigest()
    c.execute('''INSERT INTO tickets (user_id, event_id, section_id, qr_hash, status, created_at, updated_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?)''',
              (1, 1, (i % 8) + 1, qr, 'active', now, now))

# ── Demo transactions ────────────────────────────────────
for i in range(1, 6):
    section_id = (i % 8) + 1
    amounts = {1: 1000, 2: 600, 3: 300, 4: 300, 5: 200, 6: 200, 7: 200, 8: 200}
    c.execute('''INSERT INTO transactions
        (ticket_id, user_id, event_id, section_id, mpesa_receipt_number, amount, phone_number, status, channel, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)''',
        (i, 1, 1, section_id, f'SHK{7890+i}DEMO', amounts.get(section_id, 200), '254712345678', 'completed', 'stk_push', now, now))

conn.commit()
conn.close()
print(f'Created {DB_PATH}')
