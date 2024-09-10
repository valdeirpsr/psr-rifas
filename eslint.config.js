import globals from "globals";
import pluginJs from "@eslint/js";
import tseslint from "typescript-eslint";
import pluginVue from "eslint-plugin-vue";


export default [
    {
        ignores: ["**/ziggy.js", "**/global.d.ts"],
    },
    {
        languageOptions: {
            globals: {
                ...globals.browser,
                route: 'readonly',
            }
        },
    },
    pluginJs.configs.recommended,
    ...tseslint.configs.recommended,
    ...pluginVue.configs["flat/essential"],
    {
        files: ["**/*.vue"],
        languageOptions: {
            parserOptions: {
                parser: tseslint.parser
            }
        },
        rules: {
            'no-undef': 'off',
        }
    },
];
