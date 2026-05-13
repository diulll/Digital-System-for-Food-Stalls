#!/bin/bash

# Auto Git Push - Per File, 1 commit per file khusus 13-05-2026
# Usage: ./amend.sh "commit message"

COMMIT_MSG="${1:-auto update}"
BRANCH=$(git rev-parse --abbrev-ref HEAD 2>/dev/null || echo "main")

echo "🚀 Auto Git Push - Special Date: 13-05-2026"
echo "=========================================="
echo " Branch  : $BRANCH"
echo " Message : $COMMIT_MSG"
echo "=========================================="

if ! git rev-parse --git-dir > /dev/null 2>&1; then
    echo "❌ Not a git repository!"
    exit 1
fi

COUNT=0

get_type() {
    local file="$1"
    local status="$2"
    case "$status" in
        D) echo "delete" ; return ;;
    esac
    if [[ "$file" == *.sol ]]; then echo "feat"; return; fi
    if [[ "$file" == *test* || "$file" == *spec* ]]; then echo "test"; return; fi
    if [[ "$file" == *.md ]]; then echo "docs"; return; fi
    if [[ "$file" == *.env* || "$file" == *.toml || "$file" == *.json || "$file" == *.yaml || "$file" == *.yml ]]; then echo "chore"; return; fi
    if [[ "$file" == *style* || "$file" == *.css ]]; then echo "style"; return; fi
    if [[ "$file" == *config* ]]; then echo "chore"; return; fi
    if [[ "$file" == *refactor* ]]; then echo "refactor"; return; fi
    case "$status" in
        A|"??") echo "feat" ;;
        M) echo "refactor" ;;
        *) echo "chore" ;;
    esac
}

while IFS= read -r line; do
    [[ -z "$line" ]] && continue

    STATUS="${line:0:2}"
    FILE="${line:3}"
    STATUS_CLEAN="${STATUS// /}"
    TYPE=$(get_type "$FILE" "$STATUS_CLEAN")

    case "$STATUS_CLEAN" in
        D)
            echo " delete → $FILE"
            git rm --cached "$FILE" 2>/dev/null || git rm "$FILE" 2>/dev/null
            git commit -m "$TYPE($FILE): $COMMIT_MSG"
            ;;
        M|A|"??")
            echo " process → $FILE"
            git add "$FILE"
            git commit -m "$TYPE($FILE): $COMMIT_MSG"
            ;;
        *)
            echo " $STATUS_CLEAN → $FILE"
            git add "$FILE"
            git commit -m "chore($FILE): $COMMIT_MSG"
            ;;
    esac

    # --- PENGATURAN TANGGAL 13 MEI 2026 ---
    # Jam akan bertambah sesuai urutan file agar terlihat natural
    # File 1 = 10:01, File 2 = 10:02, dst.
    TIME_VAL=$(printf "%02d" $(( (COUNT % 59) + 1 )))
    COMMIT_DATE="2026-05-13 10:$TIME_VAL:00"
    
    echo " amending date → $COMMIT_DATE"
    git commit --amend --no-edit --date="$COMMIT_DATE" > /dev/null

    COUNT=$((COUNT + 1))

done < <(git status --short)

echo ""
LOCAL_AHEAD=$(git rev-list --count origin/"$BRANCH".."$BRANCH" 2>/dev/null || echo "0")

if [ "$COUNT" -gt 0 ] || [ "$LOCAL_AHEAD" -gt 0 ]; then
    echo "📤 Pushing to origin/$BRANCH..."
    if git push origin "$BRANCH" --force; then
        echo "=========================================="
        echo "✅ Berhasil! $COUNT file dikirim ke tanggal 13-05-2026."
        echo "=========================================="
    else
        echo "❌ Push gagal! Periksa koneksi atau repo origin."
        exit 1
    fi
else
    echo "=========================================="
    echo " Tidak ada perubahan file."
    echo "=========================================="
fi