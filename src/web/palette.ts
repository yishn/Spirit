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

  const palette = colorthief.getPalette(img, 5) as Color[] | null;
  if (palette == null) return null;

  palette.sort((c1, c2) => brightness(c1) - brightness(c2));

  cache.set(img, palette);
  return palette;
}

let timeout: ReturnType<typeof setTimeout> | undefined;

function updatePalette(img: HTMLImageElement) {
  clearTimeout(timeout);

  timeout = setTimeout(() => {
    const palette = getPaletteFromImg(img);
    if (palette == null) return;

    document.body.style.setProperty(
      "--main-image",
      `url("${img.getAttribute("src")}")`,
    );
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
  }, 500);
}

export function initPalette() {
  const observer = new IntersectionObserver(
    (entries) => {
      const el = entries.find(
        (entry) =>
          entry.target instanceof HTMLImageElement &&
          entry.intersectionRatio >= 0.6,
      )?.target as HTMLImageElement | undefined;

      if (el != null) {
        updatePalette(el);
      }
    },
    { threshold: 0.6 },
  );

  window.addEventListener("DOMContentLoaded", () => {
    for (const img of document.querySelectorAll("img")) {
      observer.observe(img);
    }
  });
}
