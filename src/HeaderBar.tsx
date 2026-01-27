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
            display: flex;
            height: calc(2.2 * 1.5rem);
            line-height: 2.2;
          }

          :host::part(back) {
            padding: 0.7rem;
            text-decoration: none;
          }

          :host([no-back])::part(back) {
            display: none;
          }

          :host::part(heading) {
            flex: 1;
            margin-right: 0.7rem;
            font-size: 1.5rem;
            font-weight: normal;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
          }

          :host([no-back])::part(heading) {
            margin-left: 0.7rem;
          }
        `}</Style>
      </>
    );
  }
}

defineComponents(HeaderBar);
