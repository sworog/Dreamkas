package ru.dreamkas.pos.espresso;

import android.app.Activity;
import android.test.AssertionFailedError;
import android.view.View;
import android.widget.EditText;
import android.widget.TextView;

import com.google.android.apps.common.testing.testrunner.ActivityLifecycleMonitorRegistry;
import com.google.android.apps.common.testing.testrunner.Stage;
import com.google.android.apps.common.testing.ui.espresso.NoMatchingViewException;
import com.google.android.apps.common.testing.ui.espresso.PerformException;
import com.google.android.apps.common.testing.ui.espresso.UiController;
import com.google.android.apps.common.testing.ui.espresso.ViewAction;
import com.google.android.apps.common.testing.ui.espresso.ViewAssertion;
import com.google.android.apps.common.testing.ui.espresso.matcher.BoundedMatcher;
import com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers;
import com.google.android.apps.common.testing.ui.espresso.util.HumanReadables;
import com.google.android.apps.common.testing.ui.espresso.util.TreeIterables;
import com.google.common.base.Optional;

import org.hamcrest.Description;
import org.hamcrest.Matcher;
import org.hamcrest.TypeSafeMatcher;

import java.util.ArrayList;
import java.util.Collection;
import java.util.List;
import java.util.Map;
import java.util.concurrent.TimeoutException;

import ru.dreamkas.pos.R;
import ru.dreamkas.pos.model.ReceiptItem;
import ru.dreamkas.pos.model.api.Product;

import static com.google.android.apps.common.testing.testrunner.util.Checks.checkNotNull;
import static com.google.android.apps.common.testing.ui.espresso.Espresso.onData;
import static com.google.android.apps.common.testing.ui.espresso.Espresso.onView;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.click;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.matches;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isAssignableFrom;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDisplayed;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isRoot;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withEffectiveVisibility;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withId;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withText;
import static com.google.android.apps.common.testing.ui.espresso.util.TreeIterables.breadthFirstViewTraversal;
import static com.google.common.base.Preconditions.checkArgument;
import static org.hamcrest.CoreMatchers.allOf;
import static org.hamcrest.EasyMock2Matchers.equalTo;
import static org.hamcrest.Matchers.hasEntry;
import static org.hamcrest.core.Is.is;

public class EspressoHelper
{
    public static void waitForData(Matcher matcherView, int adapterId, int millis) {
        final long startTime = System.currentTimeMillis();
        final long endTime = startTime + millis;

        do {
            try {
                onData(matcherView).inAdapterView(withId(adapterId)).check(matches(isDisplayed()));
                return;
            } catch (Throwable ex) {
                Thread.yield();
            }
        } while (System.currentTimeMillis() < endTime);
        onData(matcherView).inAdapterView(withId(adapterId)).check(matches(isDisplayed()));
    }

    public static void waitForView(Matcher matcherView, int millis) {
        final long startTime = System.currentTimeMillis();
        final long endTime = startTime + millis;

        do {
            try {
                onView(matcherView).check(matches(isDisplayed()));
                return;
            } catch (Throwable ex) {
                Thread.yield();
            }
        } while (System.currentTimeMillis() < endTime);
        onView(matcherView).check(matches(isDisplayed()));
    }

    public static void waitForView(int viewId, int millis, Matcher<? super View> waitFor) {
        final long startTime = System.currentTimeMillis();
        final long endTime = startTime + millis;

        do {
            try {
                onView(withId(viewId)).check(matches(waitFor));
                return;
            } catch (Throwable ex) {
                Thread.yield();
            }
        } while (System.currentTimeMillis() < endTime);
        onView(withId(viewId)).check(matches(waitFor));
    }

    public static void waitForView(String text, int millis) {
        final long startTime = System.currentTimeMillis();
        final long endTime = startTime + 20000;//millis;

        do {
            try {
                onView(withText(text)).check(matches(isDisplayed()));
                return;
            } catch (Throwable ex) {
                Thread.yield();
            }
        } while (System.currentTimeMillis() < endTime);
        onView(withText(text)).check(matches(isDisplayed()));
    }



    //public static ViewAction waitId(final int viewId, final long millis) {
    public static ViewAction waitId(final int viewId, final long millis) {
        return new ViewAction() {
            @Override
            public Matcher<View> getConstraints() {
                return isRoot();
            }

            @Override
            public String getDescription() {
                return "wait for a specific view with id <" + viewId + "> during " + millis + " millis.";
            }

            @Override
            public void perform(final UiController uiController, final View view) {
                uiController.loopMainThreadUntilIdle();
                final long startTime = System.currentTimeMillis();
                final long endTime = startTime + millis;
                final Matcher<View> viewMatcher = withId(viewId);

                do {
                    for (View child : breadthFirstViewTraversal(view)) {
                        // found view with required ID
                        if (viewMatcher.matches(child)) {
                            return;
                        }
                    }

                    uiController.loopMainThreadForAtLeast(50);
                }
                while (System.currentTimeMillis() < endTime);

                // timeout happens
                throw new PerformException.Builder()
                        .withActionDescription(this.getDescription())
                        .withViewDescription(HumanReadables.describe(view))
                        .withCause(new TimeoutException())
                        .build();
            }
        };
    }

    public static void waitKeyboard(int millisecs)
    {
        try
        {
            Thread.sleep(millisecs);
        }
        catch (InterruptedException e)
        {
            e.printStackTrace();
        }
    }

    public static Matcher<View> withResourceName(String resourceName)
    {
        return withResourceName(is(resourceName));
    }

    public static Matcher<? super View> hasErrorText(String expectedError)
    {
        return new ErrorTextMatcher(expectedError);
    }

    public static Matcher<View> withResourceName(final Matcher<String> resourceNameMatcher)
    {
        return new TypeSafeMatcher<View>()
        {
            @Override
            public void describeTo(Description description)
            {
                description.appendText("with resource name: ");
                resourceNameMatcher.describeTo(description);
            }

            @Override
            public boolean matchesSafely(View view)
            {
                int id = view.getId();
                return id != View.NO_ID && id != 0 && view.getResources() != null && resourceNameMatcher.matches(view.getResources().getResourceName(id));
            }
        };
    }

    private static class ErrorTextMatcher extends TypeSafeMatcher<View>
    {
        private final String mExpectedError;

        private ErrorTextMatcher(String expectedError)
        {
            mExpectedError = expectedError;
        }

        @Override
        public boolean matchesSafely(View view)
        {
            if(!(view instanceof EditText))
            {
                return false;
            }

            EditText editText = (EditText) view;

            return mExpectedError.equals(editText.getError());
        }

        @Override
        public void describeTo(Description description)
        {
            description.appendText("with error: " + mExpectedError);
        }
    }

    public static ViewAssertion has(final int expectedCount, Class<? extends View> clazz) {
        return has(expectedCount, isAssignableFrom(clazz));
    }

    /**
     * Returns a generic {@link ViewAssertion} that asserts that there is a
     * given number of descendant views that match the specified matcher.
     *
     * Example: onView(rootView).check(has(3, isAssignableFrom(EditText.class));
     *
     * @param expectedCount the number of descendant views that should match the specified matcher
     * @param selector the matcher to select the descendant views
     * @throws AssertionError if the number of views that match the selector is different from expectedCount
     */
    public static ViewAssertion has(final int expectedCount, final Matcher<View> selector) {
        return new ViewAssertion() {
            @Override
            public void check(Optional<View> view, Optional<NoMatchingViewException> noViewFoundException) {
                checkArgument(view.isPresent());
                View rootView = view.get();

                Iterable<View> descendantViews = breadthFirstViewTraversal(rootView);
                List<View> selectedViews = new ArrayList<View>();
                for (View descendantView : descendantViews) {
                    if (selector.matches(descendantView)) {
                        descendantView.toString();
                        selectedViews.add(descendantView);
                    }
                }

                if (selectedViews.size() != expectedCount) {
                    String errorMessage = HumanReadables.getViewHierarchyErrorMessage(rootView,
                            Optional.of(selectedViews),
                            String.format("Found %d views instead of %d matching: %s", selectedViews.size(), expectedCount, selector),
                            Optional.of("****MATCHES****"));
                    throw new AssertionFailedError(errorMessage);
                }
            }
        };
    }


    public static Matcher<Object> withProduct(final String roomName){
        return new BoundedMatcher<Object, Product>(Product.class) {
            @Override
            public boolean matchesSafely(Product info) {
                return info.getName().matches(roomName);
            }

            @Override
            public void describeTo(Description description) {
                description.appendText("with name: ");
            }
        };
    }

    public static Matcher<Object> withReceiptItem(final String roomName){
        return new BoundedMatcher<Object, ReceiptItem>(ReceiptItem.class) {
            @Override
            public boolean matchesSafely(ReceiptItem info) {
                return info.getProduct().getName().matches(roomName);
            }

            @Override
            public void describeTo(Description description) {
                description.appendText("with name: ");
            }
        };
    }

    public static class SetTextAction implements ViewAction {

        private CharSequence mTextToSet;

        public SetTextAction(CharSequence textToSet) {
            mTextToSet = textToSet;
        }

        @Override
        public Matcher<View> getConstraints() {
            return allOf(withEffectiveVisibility(ViewMatchers.Visibility.VISIBLE), isAssignableFrom(TextView.class));
        }

        @Override
        public String getDescription() {
            return "set text";
        }

        @Override
        public void perform(UiController uiController, View view) {
            ((TextView) view).setText(mTextToSet);
        }
    }

}

