import { defineConfig } from "@rspack/cli";
import rspack from "@rspack/core";

export default defineConfig({
  entry: {
    main: "./src/main.ts",
  },
  plugins: [
    new rspack.CopyRspackPlugin({
      patterns: [{ from: "./static" }],
    }),
  ],
});
