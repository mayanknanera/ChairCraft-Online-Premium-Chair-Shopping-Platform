<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />    
    <link rel="manifest" href="manifest.webmanifest" />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link
      href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

      <style>
        .feature-chair{
          margin: 10rem !important;
          
        }
      </style>

    <title>ChairCraft&mdash; Shop now!</title>
  </head>
  <body>
 
    <?php
  session_start();
  ?>
<?php require 'nav.php' ?>


    <main>
      <section class="section-hero">
        <div class="hero">
          <div class="hero-text-box">
            <h1 class="heading-primary">
              We design and build better chairs, for a better life
            </h1>
            <p class="hero-description">
              In a small shop in the heart of Gujarat, we spend our days relentlessly perfecting the chair. The result is a perfect blend of beauty and comfort, that will have a lasting impact on your health.
            </p>
            <a href="product.php" class="btn btn--full margin-right-sm"
              >Shop Now</a
            >

            <a href="aboutUs.php" class="btn btn--outline">Learn more &darr;</a>
            <div class="delivered-chair">
              <div class="chair-imgs">
                <img src="img/customers/customer-1.jpg" alt="Customer photo" />
                <img src="img/customers/customer-2.jpg" alt="Customer photo" />
                <img src="img/customers/customer-3.jpg" alt="Customer photo" />
                <img src="img/customers/customer-4.jpg" alt="Customer photo" />
                <img src="img/customers/customer-5.jpg" alt="Customer photo" />
                <img src="img/customers/customer-6.jpg" alt="Customer photo" />
              </div>
              <p class="chair-text">
                <span>250,000+</span> Chair delivered last year!
              </p>
            </div>
          </div>
        

              <img
                src="pic/chair-4.jpg"
                class="hero-img"
                alt="Two chair and a cup"
              />
            </picture>
          </div>
        </div>
      </section>

      <section class="section-featured">
        <div class="container">
          <h2 class="heading-featured-in">As featured in</h2>
          <div class="logos">
            <img src="img/logos/techcrunch.png" alt="Techcrunch logo" />
            <img
              src="img/logos/business-insider.png"
              alt="Business Insider logo"
            />
            <img
              src="img/logos/the-new-york-times.png"
              alt="The New York Times logo"
            />
            <img src="img/logos/forbes.png" alt="Forbes logo" />
            <img src="img/logos/usa-today.png" alt="USA Today logo" />
          </div>
        </div>
              </section>

   

        <section class="feature-chair">

         <div class="container grid grid--4-cols">
          <div class="feature">
            <ion-icon class="feature-icon" name="infinite-outline"></ion-icon>
            <p class="feature-title">Never discomfort again!</p>
            <p class="feature-text">
              Our chair gives you infinite confort for 24/7.never feel discomfort.
            </p>
          </div>
          <div class="feature">
            <ion-icon class="feature-icon" name="nutrition-outline"></ion-icon>
            <p class="feature-title">Local and organic</p>
            <p class="feature-text">
              We only use local, fresh, and organic products to build and design our chair.
            </p>
          </div>
          <div class="feature">
            <ion-icon class="feature-icon" name="leaf-outline"></ion-icon>
            <p class="feature-title">No waste</p>
            <p class="feature-text">
              All our partners only use reusable containers to package all your
              woods.
            </p>
          </div>
          <div class="feature">
            <ion-icon class="feature-icon" name="pause-outline"></ion-icon>
            <p class="feature-title">Pause anytime</p>
            <p class="feature-text">
                Not using your chair while you're away? Pause anytime, and get money back for unused days.
            </p>
          </div>
        </div>
        </section>



   <section class="section-testimonials " id="testimonials">
        <div class="testimonials-container">
          <span class="subheading">Testimonials</span>
          <h2 class="heading-secondary">Once you try it, you can't go back</h2>

          <div class="testimonials">
            <figure class="testimonial">
              <img
                class="testimonial-img"
                alt="Photo of customer Arjun Mehta"
                src="img/customers/dave.jpg"
              />
              <blockquote class="testimonial-text">
                ChairCraft makes working from home so much better. I finally have a chair that supports my back all day — worth every rupee!
              </blockquote>
              <p class="testimonial-name">&mdash; Arjun Mehta</p>
            </figure>

            <figure class="testimonial">
              <img
                class="testimonial-img"
                alt="Photo of customer Priyash Nair"
                src="img/customers/ben.jpg"
              />
              <blockquote class="testimonial-text">
              I love the pause feature! I traveled for 3 weeks, paused my subscription, and didn't waste a single rupee. Super flexible!
              </blockquote>
              <p class="testimonial-name">&mdash; Priyash Nair</p>
            </figure>

            <figure class="testimonial">
              <img
                class="testimonial-img"
                alt="Photo of customer Rohit Sharma"
                src="img/customers/steve.jpg"
              />
              <blockquote class="testimonial-text">
                The customization options are amazing. I picked my fabric, color, and cushion level — it feels like a chair made just for me.
              </blockquote>
              <p class="testimonial-name">&mdash; Rohit Sharma</p>
            </figure>

            <figure class="testimonial">
              <img
                class="testimonial-img"
                alt="Photo of customer Sneha Patel"
                src="img/customers/hannah.jpg"
              />
              <blockquote class="testimonial-text">
               ChairCraft has changed my family's living room. Comfortable, stylish, and hassle-free delivery — we're never going back to regular furniture shopping.
              </blockquote>
              <p class="testimonial-name">&mdash; Sneha Patel</p>
            </figure>
          </div>
        </div>

        <div class="gallery">
          <figure class="gallery-item">
            <img
              src="img/gallery/gallery-1.jpg"
              alt="chaircraft chairs"
            />
            <!-- <figcaption>Caption</figcaption> -->
          </figure>
          <figure class="gallery-item">
            <img
              src="img/gallery/gallery-2.jpg"
              alt="chaircraft chairs"
            />
          </figure>
          <figure class="gallery-item">
            <img
              src="img/gallery/gallery-3.jpg"
              alt="chaircraft chairs"
            />
          </figure>
          <figure class="gallery-item">
            <img
              src="img/gallery/gallery-4.jpg"
              alt="chaircraft chairs"
            />
          </figure>
          <figure class="gallery-item">
            <img
              src="img/gallery/gallery-5.jpg"
              alt="chaircraft chairs"
            />
          </figure>
          <figure class="gallery-item">
            <img
              src="img/gallery/gallery-6.jpg"
              alt="chaircraft chairs"
            />
          </figure>
          <figure class="gallery-item">
            <img
              src="img/gallery/gallery-7.jpg"
              alt="chaircraft chairs"
            />
          </figure>
          <figure class="gallery-item">
            <img
              src="img/gallery/gallery-8.jpg"
              alt="chaircraft chairs"
            />
          </figure>
          <figure class="gallery-item">
            <img
              src="img/gallery/gallery-9.jpg"
              alt="chaircraft chairs"
            />
          </figure>
          <figure class="gallery-item">
            <img
              src="img/gallery/gallery-10.jpg"
              alt="chaircraft chairs"
            />
          </figure>
          <figure class="gallery-item">
            <img
              src="img/gallery/gallery-11.jpg"
              alt="chaircraft chairs"
            />
          </figure>
          <figure class="gallery-item">
            <img
              src="img/gallery/gallery-12.jpg"
              alt="chaircraft chairs"
            />
          </figure>
        </div>
      </section>
     
      



        <section class="section-product" id="product">
        <div class="container center-text">
          <span class="subheading">Best Product</span>
          <h2 class="heading-secondary">
            Find comfort from 500+ designs
          </h2>
        </div>

        <div class="container grid grid--3-cols margin-bottom-md">
          <div class="product">
            <img
              src="img/best-chair/best-chair1.jpg"
              class="product-img"
              alt="Premium Office Chair"
            />
            <div class="product-content">
              <div class="product-tags">
                <span class="tag tag--eco">Eco-friendly</span>
              </div>
              <p class="product-title">Premium Office Chair</p>
              <ul class="product-attributes">
                <li class="product-attribute">
                  <ion-icon class="product-icon" name="flame-outline"></ion-icon>
                  <span><strong>7.5</strong> kg weight</span>
                </li>
                <li class="product-attribute">
                  <ion-icon
                    class="product-icon"
                    name="color-fill-outline"
                  ></ion-icon>
                  <span>ComfortScore &reg; <strong>74</strong></span>
                </li>
                <li class="product-attribute">
                  <ion-icon class="product-icon" name="star-outline"></ion-icon>
                  <span><strong>4.9</strong> rating (537)</span>
                </li>
              </ul>
            </div>
          </div>

          <div class="product">
            <img
              src="img/best-chair/best-chair2.jpg"
              class="product-img"
              alt="Modern Wooden Chair"
            />
            <div class="product-content">
              <div class="product-tags">
                <span class="tag tag--animal-free">Animal-Free</span>
                <span class="tag tag--natural">Natural</span>
              </div>
              <p class="product-title">Modern Wooden Chair</p>
              <ul class="product-attributes">
                <li class="product-attribute">
                  <ion-icon class="product-icon" name="flame-outline"></ion-icon>
                  <span><strong>12</strong> kg weight</span>
                </li>
                <li class="product-attribute">
                  <ion-icon
                    class="product-icon"
                    name="color-fill-outline"
                  ></ion-icon>
                  <span>EcoScore &reg; <strong>92</strong></span>
                </li>
                <li class="product-attribute">
                  <ion-icon class="product-icon" name="star-outline"></ion-icon>
                  <span><strong>4.8</strong> rating (441)</span>
                </li>
              </ul>
            </div>
          </div>

          <div class="product-detail">
            <h3 class="heading-tertiary">Comfort with:</h3>
            <ul class="list">
              <li class="list-item">
                <ion-icon class="list-icon" name="checkmark-outline"></ion-icon>
                <span>Luxury</span>
              </li>
              <li class="list-item">
                <ion-icon class="list-icon" name="checkmark-outline"></ion-icon>
                <span>Durable</span>
              </li>
              <li class="list-item">
                <ion-icon class="list-icon" name="checkmark-outline"></ion-icon>
                <span>Lightweight</span>
              </li>
              <li class="list-item">
                <ion-icon class="list-icon" name="checkmark-outline"></ion-icon>
                <span>Natural</span>
              </li>
              <li class="list-item">
                <ion-icon class="list-icon" name="checkmark-outline"></ion-icon>
                <span>Soft-Touch</span>
              </li>
              <li class="list-item">
                <ion-icon class="list-icon" name="checkmark-outline"></ion-icon>
                <span>Premium</span>
              </li>
              <li class="list-item">
                <ion-icon class="list-icon" name="checkmark-outline"></ion-icon>
                <span>Stable</span>
              </li>
              <li class="list-item">
                <ion-icon class="list-icon" name="checkmark-outline"></ion-icon>
                <span>Modern</span>
              </li>
              <li class="list-item">
                <ion-icon class="list-icon" name="checkmark-outline"></ion-icon>
                <span>Kid-friendly</span>
              </li>
               <li class="list-item">
                <ion-icon class="list-icon" name="checkmark-outline"></ion-icon>
                <span>Ergo</span>
              </li>
               <li class="list-item">
                <ion-icon class="list-icon" name="checkmark-outline"></ion-icon>
                <span>Safe</span>
              </li>
               <li class="list-item">
                <ion-icon class="list-icon" name="checkmark-outline"></ion-icon>
                <span>Adjustable</span>
              </li>
               <li class="list-item">
                <ion-icon class="list-icon" name="checkmark-outline"></ion-icon>
                <span>Compact</span>
              </li>
               <li class="list-item">
                <ion-icon class="list-icon" name="checkmark-outline"></ion-icon>
                <span>Breathable</span>
              </li>
               <li class="list-item">
                <ion-icon class="list-icon" name="checkmark-outline"></ion-icon>
                <span>Stylish</span>
              </li>
              
              

            </ul>
          </div>
        </div>

        <div class="container all-product">
          <a href="product.php" class="link">See all product &rarr;</a>
        </div>
      </section>
 </main>

  <?php require 'footer.php' ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  </body>
</html>
