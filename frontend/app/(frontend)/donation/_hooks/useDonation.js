import ENDPOINTS from "@/lib/endpoints";
import { useState } from 'react';
import { notifyToast, request } from "@/lib/helpers";
import * as Yup from 'yup';
import toast from "react-hot-toast";
import { useRouter } from "next/navigation";

export default function useDonation() {
    const [loading, setLoading] = useState(false);
    const router = useRouter();

    const initialValues = {
        amount: '',
        method_code: '',
        currency: '',
        full_name: '',
        email: '',
        mobile: '',
        message: ''
    };

    const validationSchema = Yup.object().shape({
        amount: Yup.number().required('Amount is required').positive('Amount must be positive'),
        method_code: Yup.string().required('Payment method is required'),
        currency: Yup.string().required('Currency is required'),
        full_name: Yup.string().nullable(),
        email: Yup.string().email('Invalid email').nullable(),
        mobile: Yup.string().nullable(),
        message: Yup.string().nullable(),
    });

    const handleSubmit = async (values, { resetForm }) => {
        setLoading(true);
        try {
            const { data } = await request.post(ENDPOINTS.DONATION_STORE, values, {
                headers: {
                    Accept: "application/json",
                },
            });

            if (data.status === "success") {
                if (data.data?.redirect_url) {
                    window.location.href = data.data.redirect_url;
                } else {
                    notifyToast(data);
                    resetForm();
                }
            } else {
                notifyToast(data);
            }
        } catch (error) {
            console.error(error);

            const apiMessage = error.response?.data?.message;
            const message = apiMessage || error.message || "Something went wrong";

            if (
                error.response?.status === 401 ||
                error.response?.data?.remark === "unauthenticated" ||
                apiMessage === "Unauthorized request"
            ) {
                toast.error("Please login to make a donation.");
                router.push("/login");
            } else {
                toast.error(message);
            }
        } finally {
            setLoading(false);
        }
    };

    return {
        initialValues,
        validationSchema,
        handleSubmit,
        loading,
    };
}
