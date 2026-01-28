import {
  Component,
  css,
  defineComponents,
  Else,
  If,
  prop,
  event,
  Style,
  useEffect,
  useMemo,
  useRef,
  useSignal,
} from "sinho";
import { DateIcon, LocationIcon } from "./icons.tsx";
import { updatePalette } from "../palette.ts";

export class PhotoFeed extends Component("photo-feed") {
  render() {
    return (
      <>
        <slot />

        <Style>{css`
          :host {
            display: flex;
            flex-direction: column;
          }
        `}</Style>
      </>
    );
  }
}

export class PhotoFeedItem extends Component("photo-feed-item", {
  location: prop<string | null>(null, { attribute: String }),
  locationHref: prop<string | null>(null, { attribute: String }),
  date: prop<string | null>(null, { attribute: String }),
}) {
  render() {
    return (
      <>
        <div class="spacer" />

        <slot name="img" />

        <div class="details">
          <p part="meta">
            <If condition={() => this.props.location() != null}>
              <span part="location">
                <LocationIcon />
                <If condition={() => this.props.locationHref() != null}>
                  <a href={() => this.props.locationHref()!}>
                    {this.props.location}
                  </a>
                </If>
                <Else>{this.props.location}</Else>
              </span>
            </If>
            <If condition={() => this.props.date() != null}>
              <span part="date">
                <DateIcon />

                <If condition={this.id !== ""}>
                  <a href={"#" + this.id} title="Permalink">
                    {this.props.date}
                  </a>
                </If>
                <Else>{this.props.date}</Else>
              </span>
            </If>
          </p>

          <slot />
        </div>

        <Style>{css`
          :host .spacer {
            height: var(--heading-size);
          }

          ::slotted([slot="img"]) {
            display: block;
            width: 100%;
          }

          .details {
            padding: 0 var(--standard-padding);
            margin-bottom: 2rem;
          }

          ::slotted(:not([slot="img"])) {
            margin: 0.5rem 0 !important;
          }

          [part="meta"] {
            display: flex;
            gap: 1rem;
            font-size: 0.8rem;
            opacity: 0.7;
          }

          [part="meta"] > span {
            display: inline-flex;
            align-items: center;
          }

          [part="meta"] a {
            color: inherit;
            text-decoration: none;
          }

          [part="meta"] a:hover {
            text-decoration: underline;
          }

          [part="meta"] svg {
            height: 1.1em;
            width: 1.1em;
            margin-right: 0.5em;
          }
        `}</Style>
      </>
    );
  }
}

export class PhotoFeedImageSet extends Component("photo-feed-imageset", {
  currentIndex: prop<number>(0, { attribute: Number }),
  onCurrentIndexChange: event(),
}) {
  render() {
    const galleryRef = useRef<HTMLElement>();
    const currentIndexMemo = useMemo(() => this.props.currentIndex());

    useEffect(() => {
      galleryRef()?.scrollTo({
        left: currentIndexMemo() * this.clientWidth,
        behavior: "smooth",
      });
    }, [galleryRef, currentIndexMemo]);

    return (
      <>
        <div
          ref={galleryRef}
          part="gallery"
          onscrollend={(evt) => {
            const target = evt.currentTarget as HTMLElement;
            const newIndex = Math.round(target.scrollLeft / target.clientWidth);

            if (newIndex !== this.props.currentIndex()) {
              this.currentIndex = newIndex;
              this.events.onCurrentIndexChange();
            }
          }}
        >
          <slot />
        </div>

        <Style>{css`
          [part="gallery"] {
            display: flex;
            overflow: auto;
            scroll-behavior: smooth;
            scrollbar-width: none;
            scroll-snap-type: x mandatory;
            scroll-snap-stop: always;
          }

          ::slotted(img) {
            display: block;
            width: 100%;
            scroll-snap-align: start;
          }
        `}</Style>
      </>
    );
  }
}

defineComponents(PhotoFeed, PhotoFeedItem, PhotoFeedImageSet);
