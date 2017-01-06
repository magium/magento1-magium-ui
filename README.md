The purpose of Magium is to make building and running browser tests as an everyday part of your development experience.  Magium takes a big step towards that direction by reducing the investment required to build Selenium tests by establishing a structure and framework for indvidual software products, Magento being the first one.

Magium, however, is more than just a tool to make browser testing easier for developers.  Our goal is to make web site testing not just easier, but more holistic.  In other words, while Magium's first step was in making developer's lives easier, there is no reason to stop there.  Magium Clairvoyant is a series of products under development to inject Magium into the production environment.

That is done via a new Magento module that we have built that provides the ability to build and execute simple Magium tests directly from the Magento UI based off of data that Clairvoyant can extract from the normal execution of the website.  To see how this works you can check out this short YouTube video.

[![Short version](https://img.youtube.com/vi/pRbnauL_KHE/0.jpg)](https://www.youtube.com/watch?v=pRbnauL_KHE)

You can also view a longer version that includes instructional elements here:

[![Long version](https://img.youtube.com/vi/f7NIb-p2ynU/0.jpg)](https://www.youtube.com/watch?v=f7NIb-p2ynU)

Getting started is super easy.

To install Clairvoyant you need to create a composer.json file for your Magento project that looks something like this:

```
{
    "name": "root/www",
    "require": {
    },

    "extra" : {
        "magento-deploystrategy": "copy",
        "magento-root-dir": "/var/www/magento"
    }
}
```

Then execute

```
composer require magium/magento1-clairvoyant-ui
```

That's it.  From there you can start building your tests.

The first thing you will need to do is make sure that you have Selenium Server running.  That's easy.  And Magium prefers Chromedriver, though you can configurably set it differently.

The log in to your Magento instance and navigate to `System / Configuration :: Magium` and change the settings to suit your environment.  In particular, the Selenium Server URL.

<img src="https://github.com/magium/magento1-magium-ui/blob/images/images/config.png?raw=true" style="max-width: 600px; ">

From there you can configure Clairvoyant for your individual site, though we recommend initial testing using the sample data set.  Navigate to `System / Magium / Test Configuration` and change any Xpath, URL or identity data that you need to make Clairvoyant conform to your site.  **Additionally** you need to configure how you want the test to be run: immediately, or via a queue.  Immediate mode will trigger as soon as an associated event is triggered.  Via the queue will inject the test into a queue and get executed by cron.

<img src="https://github.com/magium/magento1-magium-ui/blob/images/images/customer-identity.png?raw=true" style="max-width: 600px; ">

Once you have configured Clairvoyant it's time to start building tests.  Tests are designed to have data from Magento injected into them and get interpolated into the test.  We suggest watching the YouTube videos about to see that in action.

<img src="https://github.com/magium/magento1-magium-ui/blob/images/images/edit-test.png?raw=true" style="max-width: 600px; ">

One of the key components of building Clairvoyant tests is the use of assertions.  The assertions are extracted from Magium and presented for your consumption in the instruction form, along with navigators and actions.

<img src="https://github.com/magium/magento1-magium-ui/blob/images/images/assertions.png?raw=true" style="max-width: 600px; ">

But while building a test is good, you don't just want to build it and then run it in production; you want to test the test first.  To do that you click on the Test Execution button on the top right side of the form and you can simulate an event by injecting various Magento objects into the Magium Dependency Injection Container and executing the test with interpolation.  Again, see the video for more information about that.

When the test is completed you will see a log of the test.

<img src="https://github.com/magium/magento1-magium-ui/blob/images/images/test-execution.png?raw=true" style="max-width: 600px; ">

Once you are satisfied with your test navigate to `System / Magium / Test Execution`.  This is where you will associate the test with certain events.

<img src="https://github.com/magium/magento1-magium-ui/blob/images/images/bind-tests.png?raw=true" style="max-width: 600px; ">

Now that the test has been bound to the event, find an appropriate product and save it.  When the test has executed you can navigate to `System / Magium / Test Queue` and see the results of the test.

<img src="https://github.com/magium/magento1-magium-ui/blob/images/images/test-log.png?raw=true" style="max-width: 600px; ">

So check it out and let us know how we can make your browser testing an enjoyable experience.  Again, check out the YouTube videos if you have more questions.

[![Short version](https://img.youtube.com/vi/pRbnauL_KHE/0.jpg)](https://www.youtube.com/watch?v=pRbnauL_KHE)

You can also view a longer version that includes instructional elements here:

[![Long version](https://img.youtube.com/vi/f7NIb-p2ynU/0.jpg)](https://www.youtube.com/watch?v=f7NIb-p2ynU)

