<div class="pb-14 mb-14">
    <div class="mb-4 pb-4"></div>
    <section class="contact-us container mb-16">
        <div class="mw-930">
            <h1 class="page-title">CONTACT US</h1>
            <p>
                We’re here to help! Whether you have a question about our products, need assistance with an order, or
                want to share your feedback, the team at Little Sailors Malta is ready to assist you. Your satisfaction
                is our top priority, and we strive to provide you with the best possible customer service.
            </p>
        </div>
    </section>

    <hr class="mt-2 text-secondary "/>
    <div class="mb-4 pb-4"></div>

    <section class="contact-us container">
        <div class="mw-930 ">
            <div class="contact-us__form ">
                <form wire:submit.prevent="create" name="contact-us-form" class="needs-validation"
                      method="POST">
                    <h2 class="mb-5">Get In Touch</h2>
                    <div class="form-floating my-4">
                        <input wire:model="name" type="text" class="form-control" name="name" placeholder="Name *"
                               required="">
                        <label for="contact_us_name">Name *</label>
                        <span class="text-danger"></span>
                    </div>

                    <div class="form-floating my-4">
                        <input wire:model="email" type="email" class="form-control" name="email"
                               placeholder="Email address *" required="">
                        <label for="contact_us_name">Email address *</label>
                        <span class="text-danger"></span>
                    </div>
                    <div class="my-4">
              <textarea wire:model="message" class="form-control form-control_gray" name="comment"
                        placeholder="Your Message" cols="30"
                        rows="8" required=""></textarea>
                        <span class="text-danger"></span>
                    </div>
                    <div class="my-4">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            <div class="contact-us__form">

                <span class="font-semibold ">
                    Phone:
                </span>

                <p>
                    Give us a call at +356 7732 5545. Our customer support team is available to answer your questions
                    and provide assistance during our business hours.

                </p>
                <span class="font-semibold ">
                    Email:
                </span>
                <p>
                    For any inquiries, you can reach us via email at info@littlesailorsmalta.com. We aim to respond to
                    all emails within 24 hours, ensuring you get the support you need as quickly as possible.
                </p>
            </div>
        </div>
    </section>
</div>
