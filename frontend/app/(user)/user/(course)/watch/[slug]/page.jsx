import React from 'react';
import Course from '../_component/Course';
import ENDPOINTS from '@/lib/endpoints';
import { getApiBaseUrl, getCourseImage, getSEO, getTitle } from '@/lib/helpers';




export async function generateMetadata(props) {
    const params = await props.params;
    const { slug } = params;

    try {
        let base = getApiBaseUrl();
        
        const response = await fetch(`${base}${ENDPOINTS.DETAIL}/${slug}`);

        const data = await response.json();
        
        const seo = await getSEO({
                title: getTitle(data?.data?.course?.title),
                description: data?.data?.course?.description.replace(/<\/?[^>]+(>|$)/g, ""),
                image: getCourseImage(data?.data?.course?.image_path, data?.data?.course?.image),
            });
        
            return seo;
            
      
    } catch (error) {
        console.error("Error fetching metadata:", error);
        return { title: "Course Not Found", description: "Error loading course" };
    }
}


export default async function page(props) {
    const params = await props.params;
    const { slug } = params;
 

    return (
        <>
            <Course slug={slug}/>
        </>

    )
}
