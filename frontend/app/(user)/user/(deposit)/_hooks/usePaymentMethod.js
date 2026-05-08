import ENDPOINTS from "@/lib/endpoints";
import { request } from "@/lib/helpers";
import { useCallback } from "react";

export default function usePaymentMethod() {
    const getPaymentMethods = useCallback(async () => {
        try {
            const { data } = await request.get(ENDPOINTS.PAYMENT_METHODS);

            if (data.status === 'success') {
                return {
                    paymentMethods: data.data.methods,
                    imagePath: data.data.image_path
                };
            }
        } catch (error) {
            console.error('Error fetching payment methods:', error);
            console.error('API Response Error:', error.response);
        }
        return {
            paymentMethods: [],
            imagePath: ''
        };
    }, []);

    return { getPaymentMethods }
}
