from PIL import Image, ImageDraw
from pathlib import Path
import math

folder = Path("public/logos/kampus")

files = [
    "unsri.png",
    "polsri.png",
    "uin-raden-fatah.png",
    "bina-darma.png",
    "um-palembang.png",
    "upgri-palembang.png",
    "uigm.png",
    "tridinanti.png",
    "poltekkes.png",
    "umdp-palembang.png",
    "poltekbang-palembang.png",
    "stik-siti-khadijah.jpg",
    "akamigas.png",
    "politeknik-pariwisata-palembang.png",
    "sekolah-tinggi-ilmu-kesehatan-bina-husada.png",
    "universitas-kader-bangsa.png",
    "universitas-iba.png",
    "sekolah-tinggi-ilmu-kesehatan-mitra-adiguna.png"
]

cell_w, cell_h = 240, 190
cols = 3
rows = math.ceil(len(files) / cols)

sheet = Image.new("RGB", (cols * cell_w, rows * cell_h), "white")
draw = ImageDraw.Draw(sheet)

for i, name in enumerate(files):
    path = folder / name
    if not path.exists():
        continue

    img = Image.open(path).convert("RGBA")
    img.thumbnail((190, 130))

    x = (i % cols) * cell_w
    y = (i // cols) * cell_h

    px = x + (cell_w - img.width) // 2
    py = y + 8 + (130 - img.height) // 2

    sheet.paste(img, (px, py), img)
    draw.text((x + 8, y + 145), name, fill="black")

sheet.save("logo-kampus-preview.jpg")
print("Berhasil membuat logo-kampus-preview.jpg")
