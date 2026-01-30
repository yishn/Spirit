import { Component, css, defineComponents, Style } from "sinho";

export class MarkdownContent extends Component("markdown-content") {
  render() {
    return (
      <>
        <div class="wrapper">
          <slot />
        </div>

        <Style>{css`
          .wrapper {
            padding: 0 var(--standard-padding);
          }

          ::slotted(p) {
            margin: 0.5rem 0 !important;
          }
        `}</Style>
      </>
    );
  }
}

defineComponents(MarkdownContent);
