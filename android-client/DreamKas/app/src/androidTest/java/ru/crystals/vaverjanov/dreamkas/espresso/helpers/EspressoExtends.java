package ru.crystals.vaverjanov.dreamkas.espresso.helpers;

import android.view.View;
import android.widget.EditText;

import org.hamcrest.Description;
import org.hamcrest.Matcher;
import org.hamcrest.TypeSafeMatcher;

import static org.hamcrest.core.Is.is;

public class EspressoExtends
{
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
}
