# TODO - Fix SQL error: transactions.category has no default

- [x] Inspect current schema via migrations (done: `transactions.category` is NOT nullable in `2024_01_01_000002_create_transactions_table.php`).
- [ ] Implement **Schema + code** fix:
  - [ ] Create new migration to make `transactions.category` nullable (safe for inserts).
  - [ ] Update `ProjectController@addTransaction` to always populate legacy `category` from `expense_category_id`.
- [x] Run artisan migrate (dev) and retry the failing insert.


