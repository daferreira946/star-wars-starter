import { TypeSearchInterface } from '@/types';
import React from 'react';

interface OptionsInterface {
    id: TypeSearchInterface;
    title: string;
    placeholder: string;
}

const Options: OptionsInterface[] = [
    {
        id: 'people',
        title: 'People',
        placeholder: 'e.g. Chewbacca, Yoda, Boba Fett',
    },
    {
        id: 'films',
        title: 'Movies',
        placeholder: 'e.g. A New Hope, Empire Strikes Back',
    },
];

interface Props {
    handleSearch: (e: React.FormEvent<HTMLFormElement>) => void;
    handleTypeChange: (type: string) => void;
    type: string;
    query: string;
    setData: (key: string, value: string) => void;
    processing: boolean;
}

export default function SearchForm({ handleSearch, handleTypeChange, type, query, setData, processing }: Props) {
    const currentPlaceholder = Options.find((option) => option.id === type)?.placeholder || '';

    return (
        <form onSubmit={handleSearch} className="space-y-6">
            <fieldset>
                <div className="flex gap-6">
                    {Options.map((option) => (
                        <div key={option.id} className="flex items-center">
                            <input
                                checked={type === option.id}
                                id={option.id}
                                name="type"
                                type="radio"
                                onChange={() => handleTypeChange(option.id as TypeSearchInterface)}
                                className="radio-custom"
                            />
                            <label htmlFor={option.id} className="ml-2 text-sm font-medium text-gray-900">
                                {option.title}
                            </label>
                        </div>
                    ))}
                </div>
            </fieldset>

            <div>
                <input
                    name="query"
                    type="text"
                    value={query}
                    onChange={(e) => setData('query', e.target.value)}
                    placeholder={currentPlaceholder}
                    className="w-full rounded-md border border-gray-300 px-4 py-3 text-gray-900 placeholder-gray-400 focus:border-greenteal focus:ring-2 focus:ring-greenteal focus:outline-none"
                />
            </div>

            <button
                type="submit"
                className={
                    processing
                        ? 'w-full cursor-default rounded-2xl bg-greenteal px-4 py-3 font-medium tracking-wide text-white uppercase transition-colors duration-200'
                        : 'w-full cursor-pointer rounded-2xl bg-greenteal px-4 py-3 font-medium tracking-wide text-white uppercase transition-colors duration-200 disabled:cursor-default disabled:bg-pinkish-gray'
                }
                disabled={!query || processing}
            >
                {processing ? 'Searching...' : 'Search'}
            </button>
        </form>
    )
}
