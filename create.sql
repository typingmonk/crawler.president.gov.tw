CREATE TABLE IF NOT EXISTS gazette_article (
    id INTEGER PRIMARY KEY,
    title TEXT,
    date TEXT,
    gazette_index INTEGER,
    is_law_related INTEGER,
    matched_pattern TEXT
);
