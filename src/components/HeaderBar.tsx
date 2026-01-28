import { Component, css, defineComponents, event, prop, Style } from "sinho";
import { BackIcon } from "./icons.tsx";

export class HeaderBar extends Component("header-bar", {
  backHref: prop<string>("#", { attribute: String }),
  noBack: prop<boolean>(false, { attribute: () => true }),
  onBackClick: event(MouseEvent),
}) {
  render() {
    return (
      <>
        <a
          part="back"
          href={this.props.backHref}
          title="Back"
          onclick={this.events.onBackClick}
        >
          <BackIcon />
        </a>
        <h1 part="heading">
          <slot>Spirit</slot>
        </h1>

        <Style>{css`
          * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
          }

          :host {
            position: fixed;
            top: 0;
            left: 50%;
            width: 100%;
            max-width: var(--max-width);
            display: flex;
            height: var(--heading-size);
            background-color: var(--background-color);
            line-height: 2.2;
            z-index: 100;
            transform: translateX(-50%);
            transition: background-color 1s;
          }

          [part="back"] {
            padding: 1rem var(--standard-padding);
            color: var(--link-color);
            text-decoration: none;
            transition: color 1s;
          }

          :host([no-back]) [part="back"] {
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

          :host([no-back]) [part="heading"] {
            margin-left: var(--standard-padding);
          }
        `}</Style>
      </>
    );
  }
}

defineComponents(HeaderBar);
