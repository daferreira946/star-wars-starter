import MainLayout from '@/layouts/main';
import { router, useForm, usePage } from '@inertiajs/react';
import React, { useState } from 'react';
import { ItemInterface, TypeSearchInterface } from '@/types';
import Results from '@/components/results';
import GridDouble from '@/components/grid_double';
import GenericCard from '@/components/generic_card';
import SearchForm from '@/components/search_form';

interface PageData {
    searchResults?: ItemInterface[];
    searchQuery?: string;
    searchType?: TypeSearchInterface;
    errors: Record<string, string>;
}

interface Page {
    props: PageData;
}
export default function Home() {
    const {
        searchResults = [],
    } = usePage<PageData>().props;

    const { data, setData, post, processing } = useForm({
        type: 'people' as TypeSearchInterface,
        query: '',
    });

    const [results, setResults] = useState<ItemInterface[]>(searchResults);

    const handleTypeChange = (typeId: TypeSearchInterface) => {
        /*setData('type', typeId);
        setData('query', '');*/
        setData((previousData) => (
            {
                ...previousData,
                type: typeId,
                query: '',
            }
        ));

        if (results.length > 0) {
            setResults([]);
        }
    };

    const handleSearch = async (e: React.FormEvent) => {
        e.preventDefault();
        setResults([]);

        post(route('search'), {
            preserveScroll: true,
            onSuccess: (page: Page) => {
                setResults(page.props.searchResults as ItemInterface[])
            },
            onError: () => {
            },
        });
    };

    const handleDetailsClick = (item: ItemInterface) => {
        router.visit(`/${data.type}/${item.id}`);
    };

    return (
        <MainLayout>
            <GridDouble>
                <GenericCard>
                    <h2 className="mb-6 text-lg font-semibold text-gray-800">What are you searching for?</h2>

                    <SearchForm handleSearch={handleSearch} handleTypeChange={handleTypeChange} type={data.type} query={data.query} setData={setData} processing={processing} />
                </GenericCard>

                <GenericCard>
                    <h2 className="mb-6 border-b-1 border-pinkish-gray text-xl font-bold text-gray-800">Results</h2>

                    <Results results={results} processing={processing} handleDetailsClick={handleDetailsClick} dataType={data.type} />
                </GenericCard>
            </GridDouble>
        </MainLayout>
    );
}
