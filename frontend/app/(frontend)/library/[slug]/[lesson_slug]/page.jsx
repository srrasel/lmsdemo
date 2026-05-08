import React from 'react'
import { getApiBaseUrl, getMetaTitle } from '@/lib/helpers';
import ENDPOINTS from '@/lib/endpoints';
import LessonContent from './_components/LessonContent';
import { notFound } from 'next/navigation';

export async function generateMetadata(props) {
    const params = await props.params;
    const { lesson_slug } = params;
    return getMetaTitle(lesson_slug);
}

export default async function Page(props) {
    const params = await props.params;
    const { slug, lesson_slug } = params;
    const id = slug; // The URL param 'slug' is actually the ID now

    let book = null;
    let lesson = null;

    try {
        // Fetch book details to get context and lesson content
        // Assuming the book details API returns full structure including chapters and lessons content
        // If lessons content is not in book details, we might need a separate API call for lesson details
        // The user provided JSON for book details includes "lessons" array with "content". 
        // So fetching book details should be enough.
        
        const response = await fetch(`${getApiBaseUrl()}${ENDPOINTS.LIBRARY_BOOK_DETAILS}/${id}`, { cache: 'no-store' });
        const data = await response.json();
        
        if(data?.status === 'success' && data?.data?.book) {
            book = data.data.book;
            
            // Find the lesson
            for (const chapter of book.chapters) {
                const found = chapter.lessons.find(l => l.slug === lesson_slug);
                if (found) {
                    lesson = found;
                    break;
                }
            }
        }

    } catch (error) {
        console.error("Error fetching lesson details:", error);
    }

    if (!lesson) {
        // return notFound();
        return (
             <div className="container py-5">
                <h3>Lesson not found</h3>
             </div>
        )
    }

    return (
        <LessonContent book={book} lesson={lesson} />
    )
}
