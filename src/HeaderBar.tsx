import { Component, css, defineComponents, event, prop, Style } from "sinho";

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
          <svg
            width="100%"
            height="100%"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M19 12H5M5 12L12 19M5 12L12 5"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </svg>
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
            --heading-font-size: 1.7rem;
            display: flex;
            height: calc(2.2 * var(--heading-font-size));
            line-height: 2.2;
          }

          :host::part(back) {
            padding: 1rem var(--standard-padding);
            text-decoration: none;
          }

          :host([no-back])::part(back) {
            display: none;
          }

          :host::part(heading) {
            flex: 1;
            margin-right: var(--standard-padding);
            font-size: var(--heading-font-size);
            font-weight: normal;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
          }

          :host([no-back])::part(heading) {
            margin-left: var(--standard-padding);
          }
        `}</Style>
      </>
    );
  }
}

defineComponents(HeaderBar);
