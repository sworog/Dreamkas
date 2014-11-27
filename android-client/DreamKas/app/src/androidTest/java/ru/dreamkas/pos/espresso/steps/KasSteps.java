package ru.dreamkas.pos.espresso.steps;


import android.util.Pair;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.google.android.apps.common.testing.ui.espresso.UiController;
import com.google.android.apps.common.testing.ui.espresso.ViewAction;
import com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers;

import org.hamcrest.Matcher;

import java.util.ArrayList;

import ru.dreamkas.pos.R;
import ru.dreamkas.pos.controller.Command;
import ru.dreamkas.pos.espresso.EspressoHelper;
import ru.dreamkas.pos.model.api.Product;

import static com.google.android.apps.common.testing.ui.espresso.Espresso.onData;
import static com.google.android.apps.common.testing.ui.espresso.Espresso.onView;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.clearText;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.click;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.closeSoftKeyboard;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.typeText;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.matches;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isAssignableFrom;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDisplayed;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withEffectiveVisibility;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withId;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withText;
import static org.hamcrest.CoreMatchers.allOf;
import static org.hamcrest.core.IsNot.not;
import static ru.dreamkas.pos.espresso.EspressoHelper.has;
import static ru.dreamkas.pos.espresso.EspressoHelper.waitForData;
import static ru.dreamkas.pos.espresso.EspressoHelper.waitForView;
import static ru.dreamkas.pos.espresso.EspressoHelper.waitKeyboard;
import static ru.dreamkas.pos.espresso.EspressoHelper.withProduct;
import static ru.dreamkas.pos.espresso.EspressoHelper.withReceiptItem;

public class KasSteps {

    static Command clickOnProductInReceiptCommand = new Command<String>() {
        @Override
        public void execute(String name){
            waitForData(withReceiptItem(name), R.id.lvReceipt, 10000);
            onData(withReceiptItem(name)).inAdapterView(withId(R.id.lvReceipt)).perform(click());
        }};

    static Command clickOnProductInSearchResultCommand = new Command<String>() {
        @Override
        public void execute(String name){
            waitForData(withProduct(name), R.id.lvProductsSearchResult, 10000);
            onData(withProduct(name)).inAdapterView(withId(R.id.lvProductsSearchResult)).perform(click());
        }};

    public static void search(String searchFor) throws InterruptedException {
        search(searchFor, true);
    }
        public static void search(String searchFor, boolean waitForResult) throws InterruptedException {
        //onView(withId(R.id.txtProductSearchQuery)).perform(typeText(searchFor));
        onView(withId(R.id.txtProductSearchQuery)).perform(new EspressoHelper.SetTextAction(searchFor));
        /*if(waitForResult){
            waitForView(R.id.pbSearchProduct, 3000, isDisplayed());
            waitForView(R.id.pbSearchProduct, 20000, not(isDisplayed()));
        }*/
    }


    public static void clickOnProductInSearchResult(String name) throws Throwable {
        CommonSteps.tryInTime(clickOnProductInSearchResultCommand, name);
    }

    public static void clickOnProductInReceipt(String name) throws Throwable {
        CommonSteps.tryInTime(clickOnProductInReceiptCommand, name);
    }
}
