import useUtility from "@/app/_hooks/useUtility";
import { useSection } from "./_hooks/useSection";
import { blogImage, showDateTime, request } from "@/lib/helpers";
import ENDPOINTS from "@/lib/endpoints";

import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Pagination } from 'swiper/modules';
import Link from "next/link";
import { Image } from "react-bootstrap";
import { useEffect, useRef, useState } from "react";

export const Blog = ({ section }) => {
    const { content } = useSection(section);
    const { trans } = useUtility();
    const [blogs, setBlogs] = useState([]);
    
    // Unique refs for navigation
    const prevRef = useRef(null);
    const nextRef = useRef(null);
    const swiperRef = useRef(null);

    useEffect(() => {
        const fetchBlogs = async () => {
            try {
                const response = await request.get(ENDPOINTS.BLOGS);
                if (response.data?.data?.blogs?.data) {
                    setBlogs(response.data.data.blogs.data);
                }
            } catch (error) {
                console.error("Error fetching blogs for section:", error);
            }
        };

        fetchBlogs();
    }, []);

    useEffect(() => {
        if (swiperRef.current && swiperRef.current.swiper) {
            swiperRef.current.swiper.params.navigation.prevEl = prevRef.current;
            swiperRef.current.swiper.params.navigation.nextEl = nextRef.current;
            swiperRef.current.swiper.navigation.init();
            swiperRef.current.swiper.navigation.update();
        }
    }, [blogs]);

    return (
        <section className="blog py-50">
            <div className="container">
                <div className="slider-top">
                    <div className="section-heading style-left section-heading-dark">
                        <h2 className="section-heading__title mb-0">{trans(content?.data_values?.heading)}</h2>
                    </div>
                    <div className="swipe-btn-wrapper">
                        <div className="swiper-button-prev" ref={prevRef}></div>
                        <div className="swiper-button-next" ref={nextRef}></div>
                    </div>
                </div>
                <Swiper
                    ref={swiperRef}
                    modules={[Navigation, Pagination]}
                    navigation={{
                        nextEl: nextRef.current,
                        prevEl: prevRef.current,
                    }}
                    spaceBetween={20}
                    breakpoints={{
                        576: { slidesPerView: 2 },
                        767: { slidesPerView: 2 },
                        1024: { slidesPerView: 3 },
                        1440: { slidesPerView: 4 }
                    }}
                >
                    {blogs.map((blog, index) => (
                        <SwiperSlide className="blog-item" key={index}>
                            <div className="blog-item__thumb">
                                <Link href={`/blog-details/${blog?.id}-${blog?.slug}`} className="blog-item__thumb-link">
                                    <Image src={blogImage(blog?.image)} className="fit-image" alt="blog_image" />
                                </Link>
                            </div>
                            <div className="blog-item__content">
                                <div className="blog-item__date">
                                    <span className="icon"><i className="far fa-clock"></i></span>
                                    <span className="text">{showDateTime(blog?.created_at, 'DD MMM YYYY')}</span>
                                </div>
                                <h6 className="blog-item__title">
                                    <Link href={`/blog-details/${blog?.id}-${blog?.slug}`} className="blog-item__title-link">{trans(blog?.title)}</Link>
                                </h6>
                                <p className="blog-item__desc">
                                    {trans(blog?.preview_text)}
                                </p>
                            </div>
                        </SwiperSlide>
                    ))}
                </Swiper>

            </div>
        </section>
    );
};
