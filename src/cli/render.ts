import { extractTemplate, loadTemplate, renderTemplate } from "./template.ts";

interface IndexTemplateData {
  title: string;
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
    title: data.title,
    lists: data.lists
      .map((list) =>
        renderTemplate(listTemplate, {
          name: list.name,
          items: list.items
            .map((item) => renderTemplate(listItemTemplate, item))
            .join(""),
        }),
      )
      .join(""),
  });
}

interface JourneyTemplateData {
  name: string;
  items: {
    id: string;
    location?: string;
    date?: string;
    description?: string;
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
    name: data.name,
    items: data.items
      .map((item) =>
        item.images.length === 1
          ? renderTemplate(itemTemplate, {
              id: item.id,
              location: item.location ?? "",
              date: item.date ?? "",
              description: item.description ?? "",
              imgSrc: item.images[0].imgSrc,
            })
          : renderTemplate(itemImageSetTemplate, {
              id: item.id,
              location: item.location ?? "",
              date: item.date ?? "",
              description: item.description ?? "",
              images: item.images
                .map((img) =>
                  renderTemplate(itemImageSetImageTemplate, {
                    imgSrc: img.imgSrc,
                  }),
                )
                .join(""),
            }),
      )
      .join(""),
  });
}
