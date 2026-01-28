import { Component, css, defineComponents, prop, Style } from "sinho";

export class JourneyList extends Component("journey-list", {
  text: prop<string>("", { attribute: String }),
}) {
  render() {
    return (
      <>
        <h2>{this.props.text}</h2>

        <div part="list">
          <slot />
        </div>

        <Style>{css`
          * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
          }

          h2 {
            font-weight: normal;
            font-size: 1rem;
            padding: 1rem var(--standard-padding) 0.5rem;
          }

          [part="list"] {
            display: flex;
            flex-direction: column;
            padding-bottom: 1rem;
          }
        `}</Style>
      </>
    );
  }
}

export class JourneyListItem extends Component("journey-list-item", {
  href: prop<string>("#", { attribute: String }),
}) {
  render() {
    return (
      <>
        <a href={this.props.href}>
          <slot name="img" />
          <span class="shade" />
          <span part="text">
            <slot />
          </span>
        </a>

        <Style>{css`
          :host {
            display: grid;
            height: 6rem;
            background-color: black;
            background-size: cover;
            background-position: center;
          }

          a {
            position: relative;
            text-decoration: none;
            color: white;
            overflow: hidden;
          }

          [part="text"] {
            position: absolute;
            bottom: 0.2rem;
            left: var(--standard-padding);
            font-weight: bold;
            text-shadow: 0 0 1rem black;
          }

          .shade {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.2);
          }

          ::slotted(img) {
            position: absolute;
            top: 50%;
            left: 50%;
            width: calc(100% + 4rem);
            transform: translate(-50%, -50%);
            transition: transform 0.5s;
          }

          a:hover ::slotted(img) {
            transform: translate(calc(-50% + 2rem), -50%);
          }
        `}</Style>
      </>
    );
  }
}

defineComponents(JourneyList, JourneyListItem);
