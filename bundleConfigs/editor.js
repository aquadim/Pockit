// Конфигурация rollup для редактора
import {nodeResolve} from "@rollup/plugin-node-resolve"
export default {
  input: "./wwwroot/js/autogost.js",
  output: {
    file: "./wwwroot/js/autogost.bundle.js",
    format: "iife"
  },
  plugins: [nodeResolve()]
}
