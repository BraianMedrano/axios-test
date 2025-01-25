import axios from 'axios';
import Routing from 'fos-router';
import routes from '../public/js/fos_js_routes.json';

Routing.setRoutingData(routes);

// Function to fetch data from the the backend with the url as parameter
export async function fetchData(url){
    try {
        const response = await axios.get(url);
        // console.log(response.data);
        return response.data;
    } catch (error) {
        console.log(error);
    }
}

// Function to fetch the product details using the function fetchData
export async function fetchProductDetails(productId){
    const url = Routing.generate('app_product_show_description', { id: productId });
    const data = await fetchData(url);
    console.log(data);
}

// Function to delete a product and refresh only the table
export async function deleteProduct(productId){
    const url = Routing.generate('app_product_remove_ajax', { id: productId });

    try {
        const response = await axios.delete(url);
        
        if (response.data.success) {

            console.log(response.data.message);

            // Get the row of the product from the table
            const row = document.getElementById(`product-row-${productId}`);

            // Remove the row from the table if it exists
            if(row) row.remove();

        } else {
            console.log('Error deleting the product', response.data.error);
        }
        
    } catch (error) {
        console.log('Error deleting the product', error);
    }

    
}

// export function makeAConsoleLog(){
//     console.log("HELLO FROM AJAX_GET.JS");
// }

// window.makeAConsoleLog = makeAConsoleLog;

window.fetchProductDetails = fetchProductDetails;
window.deleteProduct = deleteProduct;
