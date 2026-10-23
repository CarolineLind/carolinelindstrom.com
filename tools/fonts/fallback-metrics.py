#--------------------------------------------------#
#---------------- FALLBACK METRICS ----------------#
#--------------------------------------------------#
#
# Calculates size-adjust and ascent/descent/line-gap overrides so a system
# font (Arial, Courier New) takes up the same space as each web font.
# Text then doesn't jump when the web font arrives (layout shift, CLS).
# Paste the printed values into carolinelindstrom/assets/css/base/fonts.css.
#
# Usage (after subset-fonts.sh has created .venv):
#   tools/fonts/.venv/bin/python3 tools/fonts/fallback-metrics.py

from pathlib import Path

from fontTools.ttLib import TTFont
from fontTools.varLib.instancer import instantiateVariableFont

HERE = Path(__file__).resolve().parent
FONTS = HERE.parent.parent / "carolinelindstrom" / "assets" / "fonts"
SYSTEM = Path("/System/Library/Fonts/Supplemental")

# Approximate Swedish letter frequencies (%) plus spaces, used to weight the
# average character width the way real Swedish text would.
FREQ = {
    " ": 18.0, "a": 9.4, "b": 1.5, "c": 1.5, "d": 4.7, "e": 10.1, "f": 2.0,
    "g": 2.9, "h": 2.1, "i": 5.8, "j": 0.6, "k": 3.1, "l": 5.3, "m": 3.5,
    "n": 8.5, "o": 4.5, "p": 1.8, "q": 0.02, "r": 8.4, "s": 6.6, "t": 7.7,
    "u": 1.9, "v": 2.4, "w": 0.1, "x": 0.2, "y": 0.7, "z": 0.07,
    "å": 1.3, "ä": 1.8, "ö": 1.3,
}

# Web font, the weight it's mostly used at, and the system font to match
PAIRS = [
    ("Bricolage Grotesque", "bricolage-grotesque.woff2", 400, "Arial.ttf", "Arial"),
    ("Bricolage Grotesque", "bricolage-grotesque.woff2", 800, "Arial Bold.ttf", "Arial Bold"),
    ("DM Sans", "dm-sans.woff2", 400, "Arial.ttf", "Arial"),
    ("DM Sans", "dm-sans.woff2", 700, "Arial Bold.ttf", "Arial Bold"),
    ("JetBrains Mono", "jetbrains-mono.woff2", 400, "Courier New.ttf", "Courier New"),
]


def avg_width(font):
    """Average advance width per em, weighted by letter frequency."""
    cmap = font.getBestCmap()
    hmtx = font["hmtx"]
    upm = font["head"].unitsPerEm
    total = weight = 0.0
    for ch, freq in FREQ.items():
        glyph = cmap.get(ord(ch))
        if glyph:
            total += hmtx[glyph][0] * freq
            weight += freq
    return total / weight / upm


def vertical(font):
    """Ascent, descent and line gap per em, from hhea (what browsers use)."""
    hhea = font["hhea"]
    upm = font["head"].unitsPerEm
    return hhea.ascent / upm, abs(hhea.descent) / upm, hhea.lineGap / upm


for name, file, wght, system_file, local_name in PAIRS:
    web = TTFont(FONTS / file)
    if "fvar" in web:
        web = instantiateVariableFont(web, {"wght": wght}, inplace=False)
    system = TTFont(SYSTEM / system_file)

    size_adjust = avg_width(web) / avg_width(system)
    ascent, descent, line_gap = vertical(web)

    print(f"/* {name} {wght} on {local_name} */")
    print(f"size-adjust: {size_adjust * 100:.2f}%;")
    print(f"ascent-override: {ascent / size_adjust * 100:.2f}%;")
    print(f"descent-override: {descent / size_adjust * 100:.2f}%;")
    print(f"line-gap-override: {line_gap / size_adjust * 100:.2f}%;")
    print()
