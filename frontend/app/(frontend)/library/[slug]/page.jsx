import React from 'react'
import { getApiBaseUrl, getMetaTitle, request } from '@/lib/helpers';
import ENDPOINTS from '@/lib/endpoints';
import BookDetails from './_components/BookDetails';
import { notFound } from 'next/navigation';

export async function generateMetadata(props) {
    const params = await props.params;
    const { slug } = params;
    return getMetaTitle(slug); // Placeholder, ideally fetch title
}

export default async function Page(props) {
    const params = await props.params;
    const { slug } = params;
    const id = slug; // The URL param 'slug' is actually the ID now

    let book = null;
    try {
        const response = await fetch(`${getApiBaseUrl()}${ENDPOINTS.LIBRARY_BOOK_DETAILS}/${id}`, { cache: 'no-store' });
        
        if (!response.ok) {
            // fallback or error
             console.error("Failed to fetch book details");
        }
        const data = await response.json();
        if(data?.status === 'success' && data?.data?.book) {
            book = data.data.book;
        }

    } catch (error) {
        console.error("Error fetching book details:", error);
    }

    if (!book) {
       // return notFound(); // Uncomment if strict 404 is desired
       // For now, let's render with empty or null to avoid crashing if API is not ready
    }

    return (
        <BookDetails book={book} />
    )
}
