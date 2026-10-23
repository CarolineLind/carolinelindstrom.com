#!/usr/bin/env bash
#--------------------------------------------------#
#------------------ SUBSET FONTS ------------------#
#--------------------------------------------------#
#
# Cuts the source fonts in src/ down to the characters the site uses and
# writes them to the theme's assets/fonts/. Re-run after changing UNICODES.
# Why and how: docs/decisions/0001-self-hosted-fonts.md
#
# Usage: tools/fonts/subset-fonts.sh

set -euo pipefail

HERE="$(cd "$(dirname "$0")" && pwd)"
SRC="$HERE/src"
OUT="$HERE/../../carolinelindstrom/assets/fonts"
VENV="$HERE/.venv"

# Characters kept in every font:
#   0020-007E  Basic Latin (A–Z, a–z, digits, punctuation)
#   00A0-00FF  Latin-1 (å ä ö é ü ñ « » © ° × etc.)
#   0152-0153  Œ œ
#   2010-2015  hyphens and dashes (– —)
#   2018-201E  curly quotes (’ “ ” „)
#   2022       bullet •
#   2026       ellipsis …
#   2039-203A  single guillemets ‹ ›
#   20AC       euro sign €
#   2122       trademark ™
#   2190-2193  arrows ← ↑ → ↓
#   2212       minus sign −
#   2713       check mark ✓ (the code section's terminal)
# Arrows and ✓ are listed for completeness, but Google's Latin files don't
# contain them, so they come from a system font. The design draws arrows as SVG.
UNICODES="U+0020-007E,U+00A0-00FF,U+0152-0153,U+2010-2015,U+2018-201E,U+2022,U+2026,U+2039-203A,U+20AC,U+2122,U+2190-2193,U+2212,U+2713"

# fonttools in a local virtual environment, so nothing is installed system-wide
if [ ! -x "$VENV/bin/pyftsubset" ]; then
	python3 -m venv "$VENV"
	"$VENV/bin/pip" install --quiet fonttools brotli
fi

mkdir -p "$OUT"

subset() {
	local in="$1" out="$2"
	"$VENV/bin/pyftsubset" "$SRC/$in" \
		--unicodes="$UNICODES" \
		--layout-features="kern,liga,calt,ccmp,locl,mark,mkmk,tnum,case" \
		--flavor=woff2 \
		--output-file="$OUT/$out"
	printf '%-40s %6s bytes\n' "$out" "$(wc -c < "$OUT/$out" | tr -d ' ')"
}

subset bricolage-grotesque-latin-opsz96-wght400-800.woff2 bricolage-grotesque.woff2
subset dm-sans-latin-wght400-700.woff2                    dm-sans.woff2
subset jetbrains-mono-latin-wght400-600.woff2             jetbrains-mono.woff2

cp "$SRC"/OFL-*.txt "$OUT/"
