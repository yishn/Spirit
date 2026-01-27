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
  resolve: {
    alias: {
      "sinho/jsx-dev-runtime": "sinho/jsx-runtime",
    },
  },
  module: {
    rules: [
      {
        test: /\.tsx?$/,
        exclude: [/node_modules/],
        loader: "builtin:swc-loader",
        options: {
          jsc: {
            parser: {
              syntax: "typescript",
              tsx: true,
            },
            target: "es2022",
            transform: {
              react: {
                runtime: "automatic",
                importSource: "sinho",
                throwIfNamespace: false,
              },
            },
          },
        },
        type: "javascript/auto",
      },
    ],
  },
});
