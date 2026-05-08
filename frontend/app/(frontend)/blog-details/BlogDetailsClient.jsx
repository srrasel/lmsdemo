"use client";

import useUtility from "@/app/_hooks/useUtility";
import { blogImage, showDateTime } from "@/lib/helpers";
import Link from "next/link";
import { usePathname } from "next/navigation";
import { Image } from "react-bootstrap";


export default function BlogDetailsClient({ blogData }) {
    const { trans } = useUtility();
    const pathname = usePathname();
    const shareUrl = `${window.location.origin}${pathname}`;

    return (
        <section className="py-50">
            <div className="container">
                <div className="row gy-5 justify-content-center">
                    <div className="col-xl-9 col-lg-8">
                        <div className="blog-details">
                            <div className="blog-details__thumb">
                                <Image
                                    src={blogImage(blogData?.blog?.image)}
                                    className="fit-image"
                                    alt={blogData?.blog?.title || "Blog Image"}
                                />
                            </div>
                            <div className="blog-details__content">
                                <span className="blog-item__date mb-2">
                                    <span className="blog-item__date-icon">
                                        <i className="las la-clock"></i>
                                    </span>
                                    {showDateTime(blogData?.blog?.created_at, 'DD MMM YYYY')}
                                </span>
                                <h3 className="blog-details__title">
                                    {blogData?.blog?.title || trans("Untitled Blog")}
                                </h3>
                                <p
                                    className="blog-details__desc"
                                    dangerouslySetInnerHTML={{
                                        __html: blogData?.blog?.description || trans("No description available."),
                                    }}
                                />
                                <div className="blog-details__share mt-4 d-flex align-items-center flex-wrap">
                                    <h5 className="social-share__title mb-0 me-sm-3 me-1 d-inline-block">
                                        {trans("Share This")}
                                    </h5>
                                    <ul className="social-list">
                                        <li className="social-list__item">
                                            <Link href={`https://www.facebook.com/sharer/sharer.php?u=${shareUrl}`} target="_blank" className="social-list__link flex-center">
                                                <i className="fab fa-facebook-f"></i>
                                            </Link>
                                        </li>
                                        <li className="social-list__item">
                                            <Link href={`https://twitter.com/intent/tweet?text=${blogData?.blog?.title}&url=${shareUrl}`} target="_blank" className="social-list__link flex-center">
                                                <i className="fas fa-times"></i>
                                            </Link>
                                        </li>
                                        <li className="social-list__item">
                                            <Link href={`http://www.linkedin.com/shareArticle?mini=true&url=${shareUrl}&title=${blogData?.blog?.title}`} target="_blank" className="social-list__link flex-center">
                                                <i className="fab fa-linkedin-in"></i>
                                            </Link>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="col-xl-3 col-lg-4">
                        <div className="blog-sidebar-wrapper">
                            <div className="blog-sidebar">
                                <h5 className="blog-sidebar__title">{trans("Related Blogs")}</h5>
                                {blogData?.related_blogs?.length > 0 ? (
                                    blogData.related_blogs.map((relatedBlog, index) => (
                                        <div className="latest-blog" key={index}>
                                            <div className="latest-blog__thumb">
                                                <Link href={`/blog-details/${relatedBlog?.id}-${relatedBlog?.slug}`}>
                                                    <Image src={blogImage(relatedBlog?.image)} className="fit-image" alt={relatedBlog?.title} />
                                                </Link>
                                            </div>
                                            <div className="latest-blog__content">
                                                <h6 className="latest-blog__title">
                                                    <Link href={`/blog-details/${relatedBlog?.id}-${relatedBlog?.slug}`}>
                                                        {relatedBlog?.title}
                                                    </Link>
                                                </h6>
                                                <span className="latest-blog__date fs-13">
                                                    {showDateTime(relatedBlog?.created_at, 'DD MMM YYYY')}
                                                </span>
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <p>{trans("No related blogs available.")}</p>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
