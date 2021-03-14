// Copyright 1999-2021. Plesk International GmbH. All rights reserved.

import Echo from 'laravel-echo';
import * as io from 'socket.io-client';

export const connection = () => {
    const config = {
        broadcaster: 'socket.io',
        client: io,
        host: 'localhost:6001',
    };

    return new Echo(config);
};
