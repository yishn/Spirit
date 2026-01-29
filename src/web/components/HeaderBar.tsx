import { Component, css, defineComponents, event, prop, Style } from "sinho";
import { BackIcon } from "./icons.tsx";

export class HeaderBar extends Component("header-bar", {
  backHref: prop<string | null>(null, { attribute: String }),
  onBackClick: event(MouseEvent),
}) {
  render() {
    return (
      <>
        <div
          class={() =>
            "wrapper " + (this.props.backHref() == null ? "noback" : "")
          }
        >
          <a
            part="back"
            href={() => this.props.backHref() ?? "#"}
            title="Back"
            onclick={this.events.onBackClick}
          >
            <BackIcon />
          </a>
          <h1 part="heading">
            <slot>Spirit</slot>
          </h1>
        </div>

        <Style>{css`
          * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
          }

          :host {
            position: fixed;
            top: 0;
            right: 0;
            width: 100%;
            height: var(--heading-size);
            background-color: var(--background-color);
            line-height: 2.2;
            transition:
              background-color 1s,
              box-shadow 1s;
            z-index: 100;
          }

          .wrapper {
            display: flex;
            margin: 0 auto;
            width: 100%;
            height: var(--heading-size);
            max-width: var(--max-width);
          }

          [part="back"] {
            padding: 1rem var(--standard-padding);
            color: var(--link-color);
            text-decoration: none;
            transition: color 1s;
          }

          .noback [part="back"] {
            display: none;
          }

          [part="heading"] {
            flex: 1;
            margin-right: var(--standard-padding);
            font-size: var(--heading-font-size);
            font-weight: normal;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
          }

          .noback [part="heading"] {
            margin-left: var(--standard-padding);
          }
        `}</Style>
      </>
    );
  }
}

defineComponents(HeaderBar);
