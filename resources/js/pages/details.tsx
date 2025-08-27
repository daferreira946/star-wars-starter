import MainLayout from '@/layouts/main';
import { Link } from '@inertiajs/react';
import { Data } from '@/types';
import Detail from '@/components/detail';

interface Props {
    data: Data;
}
export default function Details({ data }: Props) {

    return (
        <MainLayout>
            <div className="mx-auto grid max-w-6xl grid-cols-1 gap-6 h-full">
                <div className="rounded-md bg-white p-6 shadow-sm ">

                    <Detail data={data} />

                    <div className="mt-10">
                        <Link href="/" className="text-md rounded-4xl bg-greenteal px-6 py-2 font-bold text-white uppercase">
                            Back to search
                        </Link>
                    </div>
                </div>
            </div>
        </MainLayout>
    );
}
