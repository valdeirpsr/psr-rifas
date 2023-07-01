/**
 * Gambiarra que os mantenedores do ziggy me fizeram fazer
 * https://github.com/tighten/ziggy/pull/512
 * https://github.com/tighten/ziggy/pull/518
 */
import ziggyRoute, { Config as ZiggyConfig } from 'ziggy-js';
import { PageProps as AppPageProps } from './';

declare global {
  var route: ziggyRoute;
  var Ziggy: ZiggyConfig;
}

declare module 'vue' {
  interface ComponentCustomProperties {
    route: typeof ziggyRoute;
  }
}

declare module '@inertiajs/core' {
  interface PageProps extends InertiaPageProps, AppPageProps {}
}
