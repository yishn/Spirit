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
import { DateIcon, LeftIcon, LocationIcon, RightIcon } from "./icons.tsx";
import { MarkdownContent } from "./MarkdownContent.tsx";

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
  location: prop<string>("", { attribute: String }),
  locationHref: prop<string>("", { attribute: String }),
  date: prop<string>("", { attribute: String }),
}) {
  render() {
    return (
      <>
        <slot name="img" />

        <p part="meta">
          <If condition={() => this.props.location() != ""}>
            <span part="location">
              <LocationIcon />
              <If condition={() => this.props.locationHref() != ""}>
                <a href={() => this.props.locationHref()!} target="_blank">
                  {this.props.location}
                </a>
              </If>
              <Else>{this.props.location}</Else>
            </span>
          </If>
          <If condition={() => this.props.date() != ""}>
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

        <MarkdownContent>
          <slot />
        </MarkdownContent>

        <Style>{css`
          :host::before {
            content: " ";
            display: block;
            height: var(--heading-size);
            pointer-events: none;
          }

          :host(:first-child) {
            margin-top: calc(-1 * var(--heading-size) + 1rem) !important;
          }

          ::slotted([slot="img"]) {
            display: block;
            width: 100%;
          }

          [part="meta"] {
            display: flex;
            gap: 1rem;
            padding: 0 var(--standard-padding);
            margin-bottom: 1rem;
            color: var(--link-color);
            font-size: 0.8rem;
            transition: color 1s;
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
    const [length, setLength] = useSignal(0);
    const galleryRef = useRef<HTMLElement>();
    const currentIndexMemo = useMemo(() => this.props.currentIndex());

    useEffect(() => {
      this.events.onCurrentIndexChange();
    }, [currentIndexMemo]);

    useEffect(() => {
      galleryRef()?.scrollTo({
        left: currentIndexMemo() * this.clientWidth,
        behavior: "smooth",
      });
    });

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
          <slot
            onslotchange={() => {
              setLength(this.children.length);
            }}
          />
        </div>

        <If condition={() => this.props.currentIndex() > 0}>
          <a
            class="left"
            href="#"
            onclick={(evt) => {
              evt.preventDefault();

              if (this.currentIndex > 0) {
                this.currentIndex--;
              }
            }}
          >
            <LeftIcon />
          </a>
        </If>
        <If condition={() => this.props.currentIndex() < length() - 1}>
          <a
            class="right"
            href="#"
            onclick={(evt) => {
              evt.preventDefault();

              if (this.currentIndex < length() - 1) {
                this.currentIndex++;
              }
            }}
          >
            <RightIcon />
          </a>
        </If>

        <Style>{css`
          :host {
            position: relative;
          }

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

          a.left,
          a.right {
            position: absolute;
            top: 50%;
            display: grid;
            border-radius: 50%;
            padding: 0.1rem;
            color: white;
            background-color: rgba(0, 0, 0, 0.5);
            transform: translateY(-50%);
          }

          a.left svg,
          a.right svg {
            height: 1.5rem;
          }

          a.left {
            left: 0.2rem;
          }

          a.right {
            right: 0.5rem;
          }
        `}</Style>
      </>
    );
  }
}

defineComponents(PhotoFeed, PhotoFeedItem, PhotoFeedImageSet);
