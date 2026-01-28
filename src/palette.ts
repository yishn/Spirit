// @ts-ignore
import ColorThief from "colorthief";

type Color = [number, number, number];

const colorthief = new ColorThief();
const cache = new Map<HTMLImageElement, Color[]>();

function brightness([r, g, b]: Color): number {
  return 0.2126 * r + 0.7152 * g + 0.0722 * b;
}

function getPaletteFromImg(img: HTMLImageElement): Color[] | null {
  if (cache.has(img)) {
    return cache.get(img)!;
  }

  const palette = colorthief.getPalette(img, 8) as Color[] | null;
  if (palette == null) return null;

  palette.sort((c1, c2) => brightness(c1) - brightness(c2));

  cache.set(img, palette);
  return palette;
}

function getMainImg(): HTMLImageElement | undefined {
  const imgs = document.querySelectorAll("img");
  if (imgs.length === 0) return;

  return [...imgs]
    .map((img) => [img, Math.abs(window.scrollY - img.offsetTop)] as const)
    .reduce(
      ([minImg, minIndex], [img, d], i, arr) =>
        d < arr[minIndex][1] ? [img, i] : [minImg, minIndex],
      [imgs[0], 0],
    )[0];
}

let timeout: ReturnType<typeof setTimeout> | undefined;

export function updatePalette() {
  clearTimeout(timeout);

  timeout = setTimeout(() => {
    const img = getMainImg();
    if (!img) return;

    const palette = getPaletteFromImg(img);
    if (palette == null) return;

    document.body.style.setProperty(
      "--background-color",
      `rgb(${palette[0].map((x) => x * 0.8).join(", ")})`,
    );
    document.body.style.setProperty(
      "--color",
      `rgb(${palette.at(-1)!.join(", ")})`,
    );
    document.body.style.setProperty(
      "--link-color",
      `rgb(${palette.at(-2)!.join(", ")})`,
    );

    timeout = undefined;
  }, 50);
}

export function initPalette() {
  document.addEventListener("DOMContentLoaded", () => {
    for (const img of document.querySelectorAll("img")) {
      img.addEventListener("load", () => {
        updatePalette();
      });
    }
  });

  window.addEventListener("load", () => {
    updatePalette();
  });

  window.addEventListener("scroll", () => {
    updatePalette();
  });
}
