// Конфигурация для редактора
import {nodeResolve} from "@rollup/plugin-node-resolve"
export default {
  input: "./wwwroot/js/editor.mjs",
  output: {
    file: "./wwwroot/js/editor.bundle.js",
    format: "iife"
  },
  plugins: [nodeResolve()]
}
