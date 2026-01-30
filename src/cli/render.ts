import { extractTemplate, loadTemplate, renderTemplate } from "./template.ts";

export interface IndexTemplateData {
  title: string;
  content: string;
  lists: {
    name: string;
    items: {
      id: string;
      name: string;
      imgSrc: string;
    }[];
  }[];
}

export async function renderIndex(data: IndexTemplateData): Promise<string> {
  const template = await loadTemplate("index");
  const listTemplate = extractTemplate(template, "journey-list-template")!;
  const listItemTemplate = extractTemplate(
    template,
    "journey-list-item-template",
  )!;

  return renderTemplate(template, {
    ...data,
    lists: data.lists
      .map(({ items, ...list }) =>
        renderTemplate(listTemplate, {
          ...list,
          items: items
            .map((item) => renderTemplate(listItemTemplate, item))
            .join(""),
        }),
      )
      .join(""),
  });
}

export interface JourneyTemplateData {
  name: string;
  content: string;
  items: {
    id: string;
    location: string;
    date: string;
    description: string;
    images: { imgSrc: string }[];
  }[];
}

export async function renderJourney(
  data: JourneyTemplateData,
): Promise<string> {
  const template = await loadTemplate("journey");
  const itemTemplate = extractTemplate(template, "photo-feed-item-template")!;
  const itemImageSetTemplate = extractTemplate(
    template,
    "photo-feed-item-imageset-template",
  )!;
  const itemImageSetImageTemplate = extractTemplate(
    template,
    "photo-feed-item-imageset-image-template",
  )!;

  return renderTemplate(template, {
    ...data,
    items: data.items
      .map(({ images, ...item }) =>
        images.length === 1
          ? renderTemplate(itemTemplate, {
              ...item,
              imgSrc: images[0].imgSrc,
            })
          : renderTemplate(itemImageSetTemplate, {
              ...item,
              images: images
                .map((img) => renderTemplate(itemImageSetImageTemplate, img))
                .join(""),
            }),
      )
      .join(""),
  });
}
