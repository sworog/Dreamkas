package project.lighthouse.autotests.fixtures.sprint_28;

import org.jbehave.core.model.ExamplesTable;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class Us_54_5_Fixture extends Us_54_4_Fixture {

    public ExamplesTable prepareFixtureExampleTableIfThereWereNoInvoices() {
        List<Map<String, String>> mapList = new ArrayList<Map<String, String>>() {
            {
                add(new HashMap<String, String>() {
                    {
                        put("grossMarginDate", getYesterdayDate());
                        put("grossMarginSum", "60,00 р.");
                    }
                });
            }
        };
        return new ExamplesTable("").withRows(mapList);
    }

    public ExamplesTable prepareFixtureExampleTableIfThereWereInvoices() {
        List<Map<String, String>> mapList = new ArrayList<Map<String, String>>() {
            {
                add(new HashMap<String, String>() {
                    {
                        put("grossMarginDate", getYesterdayDate());
                        put("grossMarginSum", "100,00 р.");
                    }
                });
            }
        };
        return new ExamplesTable("").withRows(mapList);
    }
}
