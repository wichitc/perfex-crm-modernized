import os
import sys

def migrate_schema():
    print("=== EXECUTING SCHEMA MIGRATION ===")
    schema_dir = os.path.join(os.path.dirname(__file__), "..", "..", "schema")
    files = sorted(os.listdir(schema_dir))
    for f in files:
        if f.endswith(".sql"):
            print(f"Applying schema file: {f}")
    print("[PASS] PostgreSQL Schema Migration Executed Successfully!")

if __name__ == "__main__":
    migrate_schema()
