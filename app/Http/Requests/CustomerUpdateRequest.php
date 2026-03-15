<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare data before validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'email' => $this->email ? strtolower(trim($this->email)) : null,
            'phone' => $this->phone ? trim($this->phone) : null,
        ]);
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [

            'type' => [
                'required',
                'in:individual,business'
            ],

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                // Rule::unique('customers')->ignore($this->customer)
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20'
            ],

            'company_name' => [
                'required_if:type,business',
                'nullable',
                'string',
                'max:255'
            ],

            'address' => [
                'nullable',
                'string',
                'max:500'
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000'
            ],

            'status' => [
                'required',
                'in:active,inactive'
            ],
            'category_id' => [
                'required',
                'exists:categories,id'
            ],

            'product_id' => [
                'required',
                'exists:products,id'
            ],

        ];
    }

    /**
     * Custom error messages
     */
    public function messages(): array
    {
        return [

            'name.required' => 'Customer name is required.',

            'email.email' => 'Please enter a valid email address.',

            'company_name.required_if' =>
            'Company name is required when customer type is Business.',

            'type.required' => 'Please select customer type.',

            'status.required' => 'Customer status is required.',
        ];
    }

    /**
     * Friendly attribute names
     */
    public function attributes(): array
    {
        return [
            'name' => 'Customer Name',
            'email' => 'Email Address',
            'phone' => 'Phone Number',
            'company_name' => 'Company Name',
            'address' => 'Address',
            'notes' => 'Notes',
            'status' => 'Status'
        ];
    }
}




// check this out later
// Your idea is **partly correct**, but the **data design needs a small adjustment**. The key point is:

// > ❗ **Email should identify the customer, not the product.**

// Products/services a customer buys should be stored **separately and linked to the customer** using a **relationship**, not by repeating the email.

// Let’s clarify the correct approach.

// ---

// # 1️⃣ Correct Data Design (Recommended)

// You should have **two tables**:

// ### `customers`

// | id | name | email                                 | phone      | company_name |
// | -- | ---- | ------------------------------------- | ---------- | ------------ |
// | 1  | Ram  | [ram@gmail.com](mailto:ram@gmail.com) | 9812345678 | ABC Pvt Ltd  |

// ### `products` or `services` (purchases)

// | id | customer_id | product_name   | price |
// | -- | ----------- | -------------- | ----- |
// | 1  | 1           | Website Design | 200   |
// | 2  | 1           | Hosting        | 50    |

// Notice:

// * The **customer is stored once**
// * Products reference the customer using **customer_id**

// This avoids **duplicate data**.

// ---

// # 2️⃣ Why Email Should Stay Unique

// Email should remain unique because:

// * It identifies the customer
// * Prevents duplicate customers
// * Keeps data clean

// Example problem if email is not unique:

// | id | name      | email                                 |
// | -- | --------- | ------------------------------------- |
// | 1  | Ram       | [ram@gmail.com](mailto:ram@gmail.com) |
// | 2  | Ram Kumar | [ram@gmail.com](mailto:ram@gmail.com) |

// Now your system cannot know which one is correct.

// ---

// # 3️⃣ How Additional Products Should Work

// When a customer buys another product:

// Instead of creating a **new customer**, you should:

// 1️⃣ **Find existing customer by email**

// ```php
// $customer = Customer::where('email', $request->email)->first();
// ```

// 2️⃣ If customer exists → add product to them

// ```php
// $customer->products()->create([
//     'name' => $request->product_name,
//     'price' => $request->price,
// ]);
// ```

// 3️⃣ If customer does not exist → create customer

// ```php
// $customer = Customer::create([
//     'name' => $request->name,
//     'email' => $request->email
// ]);
// ```

// Then attach product.

// ---

// # 4️⃣ Correct Relationship

// Your current model:

// ```php
// public function services()
// {
//     return $this->hasMany(Product::class);
// }
// ```

// Better naming:

// ```php
// public function products()
// {
//     return $this->hasMany(Product::class);
// }
// ```

// ---

// Product model:

// ```php
// public function customer()
// {
//     return $this->belongsTo(Customer::class);
// }
// ```

// ---

// # 5️⃣ Example Workflow

// User creates customer:

// ```
// Email: ram@gmail.com
// Product: Website Design
// ```

// System stores:

// Customer:

// ```
// id:1
// email: ram@gmail.com
// ```

// Product:

// ```
// customer_id:1
// product: Website Design
// ```

// ---

// Later same customer buys another service:

// ```
// Email: ram@gmail.com
// Product: SEO
// ```

// System adds:

// ```
// customer_id:1
// product: SEO
// ```

// Customer record remains **one**.

// ---

// # 6️⃣ Visual Example

// Customer Dashboard:

// ```
// Customer: Ram
// Email: ram@gmail.com

// Products Purchased
// -------------------
// • Website Design
// • Hosting
// • SEO
// ```

// ---

// # 7️⃣ Best Practice Used in CRMs

// Professional CRMs like:

// * HubSpot
// * Salesforce
// * Zoho

// use this structure:

// ```
// Customer
//    ↓
// Orders / Services / Products
// ```

// NOT:

// ```
// Customer + Product in same table
// ```

// ---

// # 8️⃣ My Recommendation For Your Project

// Keep:

// ```
// customers table
// products/services table
// ```

// Link them with:

// ```
// customer_id
// ```

// And keep **email unique**.

// ---

// ✅ **Your idea (track customer by email)** is correct.
// ⚠️ But **products should be linked by `customer_id`, not email**.

// ---

// 💡 If you want, I can also show you a **much better CRM database structure** (very useful for your project):

// ```
// customers
// products
// services
// orders
// order_items
// payments
// ```

// This structure is used in **real SaaS CRM systems** and will make your project **scalable and professional**.
