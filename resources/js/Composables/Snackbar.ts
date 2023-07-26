import { show } from 'node-snackbar';
import 'node-snackbar/dist/snackbar.min.css';

export const useDanger = (text: string) =>
  show({ text, backgroundColor: '#f87272', textColor: '#000', actionTextColor: '#fff' });
