import React from 'react';

interface Props {
    children: React.ReactNode;
}

export default function GridDouble({children}: Props) {
    return (
        <div className="mx-auto grid max-w-6xl grid-cols-1 gap-6 lg:grid-cols-2">
            {children}
        </div>
    )
}
