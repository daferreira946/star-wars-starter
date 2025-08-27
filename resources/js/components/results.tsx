import { ItemInterface } from '@/types';

interface Props {
    results: ItemInterface[];
    processing: boolean;
    handleDetailsClick: (item: ItemInterface) => void;
    dataType: string;
}

export default function Results({ results, processing, handleDetailsClick, dataType }: Props) {
    return (
        <>
            {
                results.length > 0 ? (
                    results.map((item) => (
                        <div key={item.id} className="flex items-center justify-between border-b-1 border-pinkish-gray p-2">
                            <h3 className="text-md font-bold text-gray-800">{dataType === 'people' ? item.name : item.title}</h3>
                            <button
                                onClick={() => handleDetailsClick(item)}
                                className="text-md rounded-4xl bg-greenteal px-6 py-1 font-bold text-white uppercase cursor-pointer"
                            >
                                See details
                            </button>
                        </div>
                    ))
                ) : (
                    <div className="flex h-64 flex-col items-center justify-center text-center">
                        {processing ? (
                            <>
                                <p className="text-md text-gray-500">Searching...</p>
                            </>
                        ) : (
                            <>
                                <p className="text-sm text-gray-500">There are zero matches.</p>
                                <p className="mt-1 text-sm text-gray-500">Use the form to search for People or Movies.</p>
                            </>
                        )}
                    </div>
                )
            }
        </>
    );
};
