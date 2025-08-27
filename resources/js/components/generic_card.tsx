import React from 'react';

interface Props {
    children: React.ReactNode;
}
export default function GenericCard({ children }: Props) {
    return (
        <div className="rounded-lg bg-white p-6 shadow-sm">
            {children}
        </div>
    )
}
