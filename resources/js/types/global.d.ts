import type { route as routeFn } from 'ziggy-js';

declare global {
    const route: typeof routeFn;
    
    interface Window {
        Echo?: {
            private(channel: string): {
                listen(event: string, callback: (data: unknown) => void): void;
            };
            leaveChannel(channel: string): void;
        };
    }
}
