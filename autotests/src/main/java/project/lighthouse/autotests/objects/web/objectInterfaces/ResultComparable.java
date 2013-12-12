package project.lighthouse.autotests.objects.web.objectInterfaces;

import project.lighthouse.autotests.objects.web.compare.CompareResults;

import java.util.Map;

public interface ResultComparable {

    public CompareResults getCompareResults(Map<String, String> row);
}
