package ru.dreamkas.pos.espresso.steps;


import android.widget.LinearLayout;

import java.util.ArrayList;

import ru.dreamkas.pos.R;
import ru.dreamkas.pos.model.api.Product;

import static com.google.android.apps.common.testing.ui.espresso.Espresso.onData;
import static com.google.android.apps.common.testing.ui.espresso.Espresso.onView;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.typeText;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.matches;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDisplayed;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withId;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withText;
import static org.hamcrest.core.IsNot.not;
import static ru.dreamkas.pos.espresso.EspressoHelper.has;
import static ru.dreamkas.pos.espresso.EspressoHelper.waitForView;
import static ru.dreamkas.pos.espresso.EspressoHelper.withProduct;
import static ru.dreamkas.pos.espresso.EspressoHelper.withReceiptItem;

public class KasThen {
    public static void search(String searchFor) throws InterruptedException {
        onView(withId(R.id.txtProductSearchQuery)).perform(typeText(searchFor));
        waitForView(R.id.pbSearchProduct, 20000, not(isDisplayed()));
    }

    public static void checkSearchProductResult(int expectedCount, ArrayList<Product> content) {
        onView(withId(R.id.lvProductsSearchResult)).check(has(expectedCount, LinearLayout.class));
        for (Product item : content){
            onData(withProduct(item.getName())).inAdapterView(withId(R.id.lvProductsSearchResult)).check(matches(isDisplayed()));
        }
    }


    public static void checkEmptyReceipt() {
        waitForView("Для продажи добавьте в чек хотя бы один продукт.", 2000);
        waitForView(R.id.btnRegisterReceipt, 1000, not(isDisplayed()));
        waitForView(R.id.lvReceipt, 1000, not(isDisplayed()));
    }

    public static void checkReceipt(int expectedCount, ArrayList<Product> content) {
        onView(withId(R.id.lvReceipt)).check(has(expectedCount, LinearLayout.class));

        for (Product item : content){
            onData(withReceiptItem(item.getName())).inAdapterView(withId(R.id.lvReceipt)).check(matches(isDisplayed()));
        }
    }

    public static void checkReceiptTotal(String total) {
        onView(withId(R.id.btnRegisterReceipt)).check(matches(withText(total)));
    }

    public static void checkEditReceiptItemModal(String title, String productName, String sellingPrice, String quantity) {
        onView(withId(R.id.lblTotal)).check(matches(withText(title)));
        onView(withId(R.id.lblProductName)).check(matches(withText(productName)));
        onView(withId(R.id.txtSellingPrice)).check(matches(withText(sellingPrice)));
        onView(withId(R.id.txtValue)).check(matches(withText(quantity)));
    }
}
