import { compile } from "./compiler.ts";

async function main() {
  await compile("./sample");
}

try {
  await main();
} catch (err) {
  console.error(err);
  process.exit(1);
}
