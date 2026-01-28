import { Component, css, defineComponents, Else, If, prop, Style } from "sinho";
import { DateIcon, LocationIcon } from "./icons.tsx";

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
  imgsrc: prop<string | null>(null, { attribute: String }),
  location: prop<string | null>(null, { attribute: String }),
  date: prop<string | null>(null, { attribute: String }),
}) {
  render() {
    return (
      <>
        <img src={this.props.imgsrc} alt="" />

        <div class="details">
          <p part="meta">
            <If condition={() => this.props.location() != null}>
              <span part="location">
                <LocationIcon />
                <a href="#">{this.props.location}</a>
              </span>
            </If>
            <If condition={() => this.props.date() != null}>
              <span part="date">
                <DateIcon />

                <If condition={this.id !== ""}>
                  <a href={"#" + this.id}>{this.props.date}</a>
                </If>
                <Else>{this.props.date}</Else>
              </span>
            </If>
          </p>

          <slot />
        </div>

        <Style>{css`
          :host {
            scroll-snap-align: start;
          }

          img {
            display: block;
            width: 100%;
          }

          .details {
            padding: 0 var(--standard-padding);
            margin-bottom: 2rem;
          }

          ::slotted(*) {
            margin: 0.5rem 0 !important;
          }

          [part="meta"] {
            display: flex;
            gap: 1rem;
            font-size: 0.8rem;
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

defineComponents(PhotoFeed, PhotoFeedItem);
